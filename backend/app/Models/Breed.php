<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Breed extends Model
{
    protected $table = 'breeds';

    protected $fillable = [
        'name',
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function horses() : HasMany
    {
        return $this->hasMany(Horse::class);
    }
}
