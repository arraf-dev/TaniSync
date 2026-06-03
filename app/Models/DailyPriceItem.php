<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyPriceItem extends Model
{
    protected $fillable = [
        'daily_price_id',
        'commodity_id',
        'price',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function dailyPrice(): BelongsTo
    {
        return $this->belongsTo(DailyPrice::class);
    }

    public function commodity(): BelongsTo
    {
        return $this->belongsTo(Commodity::class, 'commodity_id');
    }
}
