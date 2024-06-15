<?php

/*
 * Modelis pārvalda treneru pieejamības un relācijas
 * Attiecības:
 * - pieder vienam trenerim
 * - ir viena rezervācija
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class InstructorAvailability extends Model
{
    protected $table = 'instructors_availabilities';

    protected $fillable = [
        'start_time',
        'end_time',
        'price',
        'instructor_id',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(Instructor::class);
    }

    public function reservation(): HasOne
    {
        return $this->hasOne(Reservation::class, 'instructor_availability_id', 'id');
    }
}
