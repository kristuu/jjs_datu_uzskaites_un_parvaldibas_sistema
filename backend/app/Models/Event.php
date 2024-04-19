<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Event extends Model
{
    protected $fillable = [
        'name',
        'start',
        'end',
        'event_category_id',
        'event_type_id',
        'location_id',
        'comment',
    ];

    protected $casts = [
        'start' => 'datetime',
        'end' => 'datetime',
    ];

    protected $dateFormat = 'd.m.Y H:i';

    public function eventCategory(): BelongsTo
    {
        return $this->belongsTo(EventCategory::class);
    }

    public function eventType(): BelongsTo
    {
        return $this->belongsTo(EventType::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }
}
