<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Application extends Model
{
    use HasFactory;

    protected $table = 'applications';
    protected $primaryKey = 'application_id';

    protected $fillable = [
        'employee_id',
        'position_id',
        'applicant_name',
        'applicant_age',
        'applicant_gender',
        'home_location',
        'education_status',
        'applied_at',
        'status',
    ];

    protected $casts = [
        'applied_at' => 'datetime',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employee_id', 'user_id');
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(EmployeePosition::class, 'position_id', 'position_id');
    }
}
