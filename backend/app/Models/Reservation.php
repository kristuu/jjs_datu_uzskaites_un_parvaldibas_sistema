<?php

/*
 * Modelis pārvalda rezervācijas un relācijas
 * Attiecības:
 * - pieder vienam lietotājam
 * - pieder vienam trenerim
 * - pieder vienai trenera pieejamībai
 */

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
        'start_time',
        'end_time',
    ];

    protected $casts = [
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_person_code', 'person_code');
    }

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(Instructor::class);
    }

    public function instructorAvailability(): BelongsTo
    {
        return $this->belongsTo(InstructorAvailability::class, 'instructor_availability_id', 'id');
    }
}
