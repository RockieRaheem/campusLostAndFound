<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemPhoto extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'item_id',
        'path',
        'sort_order',
    ];

    /**
     * @var array<string, string>
     */
    protected $appends = [
        'url',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function getUrlAttribute(): string
    {
        $normalizedPath = str_replace('\\', '/', $this->path);

        return '/storage/' . ltrim($normalizedPath, '/');
    }
}
