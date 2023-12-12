<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Horse extends Model
{
    protected $table = 'horses';
    protected $primaryKey = 'passport_number';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'passport_number',
        'name',
        'birthdate',
        'breed_id',
        'owner_person_code',
    ];

    protected $casts = [
        'birthdate' => 'date',
    ];

    protected $with = ['breed', 'owner'];

    public function breed() : BelongsTo
    {
        return $this->belongsTo(Breed::class);
    }

    public function owner() : BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_person_code', 'person_code');
    }
}
