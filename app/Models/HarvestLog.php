<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HarvestLog extends Model
{
    protected $table = 'catatan_panen';

    protected $fillable = [
        'user_id',
        'commodity_id',
        'harvest_date',
        'location',
        'quantity',
        'unit',
        'quality',
        'note',
        'status',
    ];

    protected $casts = [
        'harvest_date' => 'date',
        'quantity' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function commodity(): BelongsTo
    {
        return $this->belongsTo(Commodity::class, 'commodity_id');
    }
}
