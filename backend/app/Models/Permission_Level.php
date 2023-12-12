<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission_Level extends Model
{
    protected $table = 'permission_levels';
    protected $fillable = [
        'name',
    ];

    public function users() : BelongsToMany
    {
        return $this->belongsToMany(User::class,
        'users_permission_levels',
        'permission_level_id',
        'user_person_code');
    }
}
