<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class users_permission_level extends Model
{
    protected $fillable = [
        'user_person_code',
        'permission_level_id',
    ];
}
