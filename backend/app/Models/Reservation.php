<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    protected $table = 'reservations';

    protected $fillable = [
        'start_time',
        'end_time',
        'status',
        'user_person_code',
        'instructor_id',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'user_person_code', 'person_code');
    }

    public function instructor() : BelongsTo
    {
        return $this->belongsTo(Instructor::class);
    }
}
