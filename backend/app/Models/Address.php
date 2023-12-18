<?php

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
        'region_id',
        'postal_code',
    ];

    public function region() : BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function user() : HasOne
    {
        return $this->hasOne(User::class);
    }
}
