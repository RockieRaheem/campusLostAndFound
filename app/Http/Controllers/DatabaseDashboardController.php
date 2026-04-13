<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        abort_unless($user && $user->isAdmin(), 403, 'Only administrators can access Database UI.');
        abort_unless(app()->environment('local'), 403, 'Database UI is available only in local environment.');

        $connection = DB::connection();
        $driver = $connection->getDriverName();
        $databaseName = $connection->getDatabaseName();

        $tables = $this->getTableNames($driver, $databaseName);
        sort($tables);

        $tableStats = collect($tables)->map(function (string $table): array {
            $columns = Schema::getColumnListing($table);

            return [
                'name' => $table,
                'columns' => count($columns),
                'rows' => $this->safeRowCount($table),
            ];
        })->values();

        $selectedTable = $request->string('table')->toString();
        if ($selectedTable === '' || ! in_array($selectedTable, $tables, true)) {
            $selectedTable = $tables[0] ?? null;
        }

        $limit = max(5, min((int) $request->input('limit', 20), 100));
        $columns = [];
        $rows = collect();

        if ($selectedTable !== null) {
            $columns = Schema::getColumnListing($selectedTable);

            if (count($columns) > 0) {
                $query = DB::table($selectedTable);

                if (in_array('id', $columns, true)) {
                    $query->orderByDesc('id');
                }

                $rows = $query->limit($limit)->get();
            }
        }

        $selectedStats = $tableStats->firstWhere('name', $selectedTable);

        return view('database.index', [
            'driver' => $driver,
            'databaseName' => $databaseName,
            'tableStats' => $tableStats,
            'selectedTable' => $selectedTable,
            'selectedStats' => $selectedStats,
            'columns' => $columns,
            'rows' => $rows,
            'limit' => $limit,
        ]);
    }

    private function safeRowCount(string $table): int
    {
        try {
            return (int) DB::table($table)->count();
        } catch (\Throwable) {
            return -1;
        }
    }

    private function getTableNames(string $driver, ?string $databaseName): array
    {
        if ($driver === 'mysql') {
            $rows = DB::select('SHOW TABLES');
            $tableKey = $databaseName !== null ? 'Tables_in_' . $databaseName : null;

            return collect($rows)
                ->map(function (object $row) use ($tableKey): ?string {
                    if ($tableKey !== null && property_exists($row, $tableKey)) {
                        return (string) $row->{$tableKey};
                    }

                    $firstValue = array_values((array) $row)[0] ?? null;

                    return $firstValue !== null ? (string) $firstValue : null;
                })
                ->filter()
                ->values()
                ->all();
        }

        if ($driver === 'sqlite') {
            return collect(DB::select("SELECT name FROM sqlite_master WHERE type = 'table' AND name NOT LIKE 'sqlite_%' ORDER BY name"))
                ->map(fn (object $row): string => (string) $row->name)
                ->values()
                ->all();
        }

        if ($driver === 'pgsql') {
            return collect(DB::select("SELECT tablename FROM pg_tables WHERE schemaname = 'public' ORDER BY tablename"))
                ->map(fn (object $row): string => (string) $row->tablename)
                ->values()
                ->all();
        }

        return [];
    }
}
