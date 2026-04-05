<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;

    protected $table = 'events';
    protected $primaryKey = 'event_id';

    protected $fillable = [
        'fair_id',
        'vendor_id',
        'name',
        'event_date',
        'start_time',
        'end_time',
        'ticket_price',
        'max_capacity',
        'tickets_sold',
    ];

    protected $casts = [
        'event_date' => 'date',
        'ticket_price' => 'decimal:2',
    ];

    public function fair(): BelongsTo
    {
        return $this->belongsTo(Fair::class, 'fair_id', 'fair_id');
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'vendor_id', 'user_id');
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(EventTicket::class, 'event_id', 'event_id');
    }
}
