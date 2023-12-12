<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Certificate extends Model
{
    protected $table = 'certificates';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'expiration_date',
        'category_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'expiration_date' => 'date',
    ];

    protected $with = ['category'];

    /**
     * Get the category that the certificate belongs to.
     */
    public function category() : BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function instructor() : HasOne
    {
        return $this->hasOne(Instructor::class);
    }
}
