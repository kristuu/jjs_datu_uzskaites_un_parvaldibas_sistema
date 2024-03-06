<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    protected $table = 'reservations';

    protected $fillable = [
        'instructor_availability_id',
        'status',
        'user_person_code',
        'instructor_id',
    ];

    protected $casts = [
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'user_person_code', 'person_code');
    }

    public function instructor() : BelongsTo
    {
        return $this->belongsTo(Instructor::class);
    }

    public function instructors_availability() : BelongsTo
    {
        return $this->belongsTo(instructors_availability::class, 'instructor_availability_id', 'id');
    }
}
