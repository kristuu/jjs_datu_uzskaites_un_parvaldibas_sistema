<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $table = 'categories';

    protected $fillable = [
        'name',
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function certificates() : HasMany
    {
        return $this->hasMany(Certificate::class);
    }
}
