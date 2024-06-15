<?php

/*
 * Modelis pārvalda paziņojumus un relācijas
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'user_person_code',
        'short_message',
        'long_message',
    ];
}
