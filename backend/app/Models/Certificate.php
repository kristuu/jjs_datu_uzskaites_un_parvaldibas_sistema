<?php

namespace App\Models;


/*
 * Modelis pārvalda sertifikātus un relācijas
 * Attiecības:
 * - pieder vienai kategorijai
 * - ir viens instruktors
 */

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Certificate extends Model
{
    protected $table = 'certificates';

    protected $fillable = [
        'expiration_date',
        'category_id',
    ];

    protected $casts = [
        'expiration_date' => 'date:d.m.Y',
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    protected $with = ['category'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function instructor(): HasOne
    {
        return $this->hasOne(Instructor::class);
    }
}
