<?php

/*
 * Modelis pārvalda ar adresēm saistītu informāciju un relācijas
 * Attiecības:
 * - pieder vienai pilsētai
 * - ir viena lietotāja adrese
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Address extends Model
{
    protected $fillable = [
        'address_line1',
        'address_line2',
        'city',
        'city_id',
        'postal_code',
    ];

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }
}
