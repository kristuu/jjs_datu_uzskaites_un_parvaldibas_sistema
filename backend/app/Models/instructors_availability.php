<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class instructors_availability extends Model
{
    protected $table = 'instructors_availabilities';

    protected $fillable = [
        'start_time',
        'end_time',
        'instructor_id',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function instructor() : BelongsTo
    {
        return $this->belongsTo(Instructor::class);
    }

    public function reservation() : HasOne
    {
        return $this->hasOne(Reservation::class, 'instructor_availability_id', 'id');
    }
}
