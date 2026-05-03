<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'village',
        'role',
        'account_status',
        'approved_at',
        'approved_by',
        'rejected_at',
        'password',
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
            'approved_at' => 'datetime',
            'rejected_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function dashboardRoute(): string
    {
        if ($this->role === 'admin' && ! $this->isActive()) {
            return route('account.pending');
        }

        return $this->role === 'admin' ? route('admin.dashboard') : route('petani.dashboard');
    }

    public function isActive(): bool
    {
        return $this->account_status === 'active';
    }

    public function isPendingApproval(): bool
    {
        return $this->account_status === 'pending';
    }

    public function isRejected(): bool
    {
        return $this->account_status === 'rejected';
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(self::class, 'approved_by');
    }

    public function harvestLogs(): HasMany
    {
        return $this->hasMany(HarvestLog::class);
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }
}
