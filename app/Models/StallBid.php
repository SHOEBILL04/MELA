<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StallBid extends Model
{
    use HasFactory;

    protected $table = 'stall_bids';
    protected $primaryKey = 'bid_id';

    protected $fillable = [
        'vendor_id',
        'stall_id',
        'bid_amount',
        'status',
    ];

    protected $casts = [
        'bid_amount' => 'decimal:2',
    ];

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'vendor_id', 'user_id');
    }

    public function stall(): BelongsTo
    {
        return $this->belongsTo(Stall::class, 'stall_id', 'stall_id');
    }
}
