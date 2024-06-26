<?php

/*
 * Modelis pārvalda valstis un relācijas
 * Attiecības:
 * - ir daudz pilsētu
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Country extends Model
{
    protected $fillable = [
        'name',
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function city(): HasMany
    {
        return $this->hasMany(City::class);
    }
}
