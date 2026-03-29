<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Item Model
 * 
 * This class represents a lost or found item in the system.
 * It demonstrates the use of Object-Oriented Programming concepts:
 * - Class: Item is a class that defines the structure of item data
 * - Object: Each record created from this model is an object instance
 * - Encapsulation: The $fillable property protects data by specifying
 *   which attributes can be mass assigned
 */
class Item extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'items';

    /**
     * The attributes that are mass assignable.
     * 
     * This demonstrates ENCAPSULATION - protecting the object's data
     * by only allowing specific fields to be filled.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'item_name',
        'description',
        'location',
        'status',
        'claimed_at',
        'user_id',
        'claimant_info'
    ];

    /**
     * Attribute casting rules.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'claimed_at' => 'datetime',
    ];

    public function photos(): HasMany
    {
        return $this->hasMany(ItemPhoto::class)->orderBy('sort_order')->orderBy('id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getPrimaryPhotoUrlAttribute(): ?string
    {
        $firstPhoto = $this->photos->first();

        return $firstPhoto?->url;
    }
}
