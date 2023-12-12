<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'person_code';
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'person_code',
        'name',
        'surname',
        'birthdate',
        'email',
        'password',
        'phone',
        'googleplaces_address_code',
        'iban_code',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function permissionLevels() : BelongsToMany
    {
        return $this->belongsToMany(Permission_Level::class,
        'users_permission_levels',
        'user_person_code',
        'permission_level_id');
    }

    public function instructor() : HasMany
    {
        return $this->hasMany(Instructor::class, 'user_person_code', 'person_code');
    }

    public function reservations() : HasMany
    {
        return $this->hasMany(Reservation::class, 'user_person_code', 'person_code');
    }
}
