@extends('layouts.app')

@section('title', 'Database UI | Campus Lost & Found')

@section('content')
<section class="border-b border-slate-200 bg-white">
    <div class="mx-auto w-full max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <h1 class="text-4xl font-black tracking-tight text-slate-900 sm:text-5xl">Database UI</h1>
        <p class="mt-3 max-w-3xl text-slate-600">Browse your application tables and preview records directly in the browser.</p>
    </div>
</section>

<section class="mx-auto w-full max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
        <aside class="panel p-4 lg:col-span-4">
            <div class="mb-4">
                <h2 class="text-lg font-bold text-slate-900">Connection</h2>
                <p class="mt-1 text-sm text-slate-600">Driver: <span class="font-semibold text-slate-800">{{ $driver }}</span></p>
                <p class="text-sm text-slate-600">Database: <span class="font-semibold text-slate-800">{{ $databaseName }}</span></p>
                <p class="text-sm text-slate-600">Tables: <span class="font-semibold text-slate-800">{{ $tableStats->count() }}</span></p>
            </div>

            <div class="max-h-[560px] space-y-2 overflow-y-auto pr-1">
                @forelse($tableStats as $table)
                    <a
                        href="{{ route('database.index', ['table' => $table['name'], 'limit' => $limit]) }}"
                        class="block rounded-xl border px-4 py-3 transition {{ $selectedTable === $table['name'] ? 'border-primary bg-slate-50 shadow-sm' : 'border-slate-200 bg-white hover:border-slate-300' }}"
                    >
                        <p class="truncate text-sm font-semibold text-slate-900">{{ $table['name'] }}</p>
                        <p class="mt-1 text-xs text-slate-500">
                            Rows: {{ $table['rows'] >= 0 ? number_format($table['rows']) : 'n/a' }}
                            <span class="mx-1">|</span>
                            Columns: {{ $table['columns'] }}
                        </p>
                    </a>
                @empty
                    <div class="rounded-lg border border-dashed border-slate-300 bg-slate-50 p-4 text-sm text-slate-600">
                        No tables found for this connection.
                    </div>
                @endforelse
            </div>
        </aside>

        <section class="panel overflow-hidden lg:col-span-8">
            <div class="flex flex-col gap-3 border-b border-slate-200 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">{{ $selectedTable ?? 'No Table Selected' }}</h2>
                    @if($selectedStats)
                        <p class="text-sm text-slate-600">{{ $selectedStats['columns'] }} columns | {{ $selectedStats['rows'] >= 0 ? number_format($selectedStats['rows']) : 'n/a' }} rows total</p>
                    @endif
                </div>

                @if($selectedTable)
                    <form method="GET" action="{{ route('database.index') }}" class="flex items-center gap-2">
                        <input type="hidden" name="table" value="{{ $selectedTable }}">
                        <label for="limit" class="text-sm font-semibold text-slate-700">Rows</label>
                        <select id="limit" name="limit" class="field-input !w-auto !py-2 !pl-3 !pr-8 text-sm" onchange="this.form.submit()">
                            @foreach([10, 20, 50, 100] as $option)
                                <option value="{{ $option }}" {{ $limit === $option ? 'selected' : '' }}>{{ $option }}</option>
                            @endforeach
                        </select>
                    </form>
                @endif
            </div>

            @if($selectedTable && count($columns) > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 text-sm">
                        <thead class="bg-slate-50">
                            <tr>
                                @foreach($columns as $column)
                                    <th class="whitespace-nowrap px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-500">{{ $column }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @forelse($rows as $row)
                                <tr class="align-top">
                                    @foreach($columns as $column)
                                        @php($value = $row->{$column} ?? null)
                                        <td class="max-w-[260px] whitespace-pre-wrap break-words px-4 py-3 text-slate-700">
                                            @if(is_null($value))
                                                <span class="italic text-slate-400">NULL</span>
                                            @elseif(is_bool($value))
                                                {{ $value ? 'true' : 'false' }}
                                            @elseif(is_array($value) || is_object($value))
                                                {{ json_encode($value, JSON_UNESCAPED_SLASHES) }}
                                            @elseif(is_string($value) && strlen($value) > 140)
                                                {{ \Illuminate\Support\Str::limit($value, 140) }}
                                            @else
                                                {{ (string) $value }}
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ count($columns) }}" class="px-4 py-8 text-center text-sm text-slate-500">
                                        This table is currently empty.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-8 text-sm text-slate-600">
                    Select a table from the left panel to preview data.
                </div>
            @endif
        </section>
    </div>
</section>
@endsection
