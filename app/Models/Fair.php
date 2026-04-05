<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Fair extends Model
{
    use HasFactory;

    protected $table = 'fairs';
    protected $primaryKey = 'fair_id';

    protected $fillable = [
        'admin_id',
        'name',
        'location',
        'start_date',
        'end_date',
        'total_stalls',
        'status',
        'default_ticket_price',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'default_ticket_price' => 'decimal:2',
    ];

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id', 'user_id');
    }

    public function days(): HasMany
    {
        return $this->hasMany(FairDay::class, 'fair_id', 'fair_id');
    }

    public function stalls(): HasMany
    {
        return $this->hasMany(Stall::class, 'fair_id', 'fair_id');
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class, 'fair_id', 'fair_id');
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(FairTicket::class, 'fair_id', 'fair_id');
    }
}
