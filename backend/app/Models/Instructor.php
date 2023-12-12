<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Instructor extends Model
{

    protected $table = 'instructors';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'job_start_date',
        'user_person_code',
        'certificate_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'job_start_date' => 'date',
    ];

    /**
     * Get the user that is the instructor.
     */
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'user_person_code', 'person_code');
    }

    public function certificate() : BelongsTo
    {
        return $this->belongsTo(Certificate::class);
    }

    public function availability() : HasMany
    {
        return $this->hasMany(instructors_availability::class);
    }

    public function reservations() : HasMany
    {
        return $this->hasMany(Reservation::class);
    }

}
