<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Stall extends Model
{
    use HasFactory;

    protected $table = 'stalls';
    protected $primaryKey = 'stall_id';

    protected $fillable = [
        'fair_id',
        'vendor_id',
        'stall_number',
        'category',
        'max_employees',
        'price',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function fair(): BelongsTo
    {
        return $this->belongsTo(Fair::class, 'fair_id', 'fair_id');
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'vendor_id', 'user_id');
    }

    public function positions(): HasMany
    {
        return $this->hasMany(EmployeePosition::class, 'stall_id', 'stall_id');
    }

    public function bids(): HasMany
    {
        return $this->hasMany(StallBid::class, 'stall_id', 'stall_id');
    }
}
