<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'user_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function administeredFairs(): HasMany
    {
        return $this->hasMany(Fair::class, 'admin_id', 'user_id');
    }

    public function ownedStalls(): HasMany
    {
        return $this->hasMany(Stall::class, 'vendor_id', 'user_id');
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class, 'employee_id', 'user_id');
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class, 'vendor_id', 'user_id');
    }

    public function fairTickets(): HasMany
    {
        return $this->hasMany(FairTicket::class, 'visitor_id', 'user_id');
    }

    public function eventTickets(): HasMany
    {
        return $this->hasMany(EventTicket::class, 'visitor_id', 'user_id');
    }

    public function stallBids(): HasMany
    {
        return $this->hasMany(StallBid::class, 'vendor_id', 'user_id');
    }
}
