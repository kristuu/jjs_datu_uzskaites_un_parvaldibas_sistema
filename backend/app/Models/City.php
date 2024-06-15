<?php

/*
 * Modelis pārvalda pilsētas un relācijas
 * Attiecības:
 * - pieder vienai valstij
 * - ir daudz adrešu
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class City extends Model
{
    protected $fillable = [
        'name',
        'country_id',
    ];

    protected $hidden = [
        'country_id',
        'created_at', 'updated_at'
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function address(): HasMany
    {
        return $this->hasMany(Address::class);
    }
}
