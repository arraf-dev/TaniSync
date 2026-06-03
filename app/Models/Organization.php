<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'type',
        'region',
        'address',
        'status',
        'approved_at',
        'approved_by',
        'rejected_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (Organization $organization): void {
            if (! $organization->slug) {
                $organization->slug = Str::slug($organization->name);
            }
        });
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function admins(): HasMany
    {
        return $this->hasMany(User::class)->where('role', 'admin');
    }

    public function commodities(): HasMany
    {
        return $this->hasMany(Commodity::class);
    }

    public function markets(): HasMany
    {
        return $this->hasMany(Market::class);
    }

    public function dailyPrices(): HasMany
    {
        return $this->hasMany(DailyPrice::class);
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
