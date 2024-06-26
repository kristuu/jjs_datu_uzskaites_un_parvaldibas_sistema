<?php

/*
 * Modelis pārvalda lietotājus un relācijas
 * Attiecības:
 * - ir viena adrese
 * - ir daudz atļauju līmeņu
 * - ir daudz treneru
 * - ir daudz rezervāciju
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use LaravelAndVueJS\Traits\LaravelPermissionToVueJS;
use Propaganistas\LaravelPhone\Casts\E164PhoneNumberCast;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use LaravelPermissionToVueJS;
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    public $incrementing = false;
    protected $table = 'users';
    protected $primaryKey = 'person_code';
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
        'profile_picture',
        'address_id',
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
        'created_at', 'updated_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'birthdate' => 'date:d.m.Y',
        'password' => 'hashed',
        'phone' => E164PhoneNumberCast::class,
    ];

    public function setPasswordAttribute($value)
    {
        $this->attributes["password"] = bcrypt($value);
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function permissionLevels(): BelongsToMany
    {
        return $this->belongsToMany(Permission_Level::class,
            'users_permission_levels',
            'user_person_code',
            'permission_level_id');
    }

    public function instructor(): HasMany
    {
        return $this->hasMany(Instructor::class, 'user_person_code', 'person_code');
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class, 'user_person_code', 'person_code');
    }
}
