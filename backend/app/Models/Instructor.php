<?php

/*
 * Modelis pārvalda trenerus un relācijas
 * Attiecības:
 * - pieder vienam lietotājam
 * - pieder vienam sertifikātam
 * - ir daudz pieejamību
 * - ir daudz rezervāciju
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Instructor extends Model
{

    protected $table = 'instructors';

    protected $fillable = [
        'job_start_date',
        'user_person_code',
        'certificate_id',
        'short_description',
        'description'
    ];

    protected $casts = [
        'job_start_date' => 'date:d.m.Y',
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_person_code', 'person_code');
    }

    public function certificate(): BelongsTo
    {
        return $this->belongsTo(Certificate::class);
    }

    public function availabilities()
    {
        return $this->hasMany(InstructorAvailability::class);
    }

    public function reservation(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

}
