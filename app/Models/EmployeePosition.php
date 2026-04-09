<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EmployeePosition extends Model
{
    use HasFactory;

    protected $table = 'employee_positions';
    protected $primaryKey = 'position_id';

    protected $fillable = [
        'stall_id',
        'title',
        'status',
        'salary',
    ];

    protected $casts = [
        'salary' => 'decimal:2',
    ];

    public function stall(): BelongsTo
    {
        return $this->belongsTo(Stall::class, 'stall_id', 'stall_id');
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class, 'position_id', 'position_id');
    }
}
