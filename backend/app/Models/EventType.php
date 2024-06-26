<?php

/*
 * Modelis pārvalda pasākumu veidus un relācijas
 * Attiecības:
 * - ir daudz pasākumu
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EventType extends Model
{
    protected $fillable = [
        'name',
    ];

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }
}
