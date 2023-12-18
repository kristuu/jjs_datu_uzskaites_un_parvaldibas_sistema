<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = [
        'address_line1',
        'address_line2',
        'city',
        'region_id',
        'postal_code',
    ];
}
