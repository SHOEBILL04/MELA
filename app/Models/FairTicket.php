<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FairTicket extends Model
{
    use HasFactory;

    protected $table = 'fair_tickets';
    protected $primaryKey = 'ticket_id';

    protected $fillable = [
        'visitor_id',
        'fair_id',
        'day_id',
        'purchase_date',
        'ticket_price',
        'qr_code',
    ];

    protected $casts = [
        'purchase_date' => 'datetime',
        'ticket_price' => 'decimal:2',
    ];

    public function visitor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'visitor_id', 'user_id');
    }

    public function fair(): BelongsTo
    {
        return $this->belongsTo(Fair::class, 'fair_id', 'fair_id');
    }

    public function day(): BelongsTo
    {
        return $this->belongsTo(FairDay::class, 'day_id', 'day_id');
    }

    public function eventTickets(): HasMany
    {
        return $this->hasMany(EventTicket::class, 'fair_ticket_id', 'ticket_id');
    }
}
