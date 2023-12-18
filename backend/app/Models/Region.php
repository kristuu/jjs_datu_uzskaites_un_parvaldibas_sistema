<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Region extends Model
{
    protected $fillable = [
        'name',
        'country_id',
    ];

    public function country() : BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function address() : HasMany
    {
        return $this->hasMany(Address::class);
    }
}
