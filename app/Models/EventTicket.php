<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventTicket extends Model
{
    use HasFactory;

    protected $table = 'event_tickets';
    protected $primaryKey = 'event_ticket_id';

    protected $fillable = [
        'visitor_id',
        'event_id',
        'fair_ticket_id',
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

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'event_id', 'event_id');
    }

    public function fairTicket(): BelongsTo
    {
        return $this->belongsTo(FairTicket::class, 'fair_ticket_id', 'ticket_id');
    }
}
