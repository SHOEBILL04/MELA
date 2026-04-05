<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FairDay extends Model
{
    use HasFactory;

    protected $table = 'fair_days';
    protected $primaryKey = 'day_id';

    protected $fillable = [
        'fair_id',
        'day_date',
        'max_visitors',
        'visitors_count',
    ];

    protected $casts = [
        'day_date' => 'date',
    ];

    public function fair(): BelongsTo
    {
        return $this->belongsTo(Fair::class, 'fair_id', 'fair_id');
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(FairTicket::class, 'day_id', 'day_id');
    }
}
