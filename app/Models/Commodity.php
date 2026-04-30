<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Commodity extends Model
{
    protected $table = 'komoditas';

    protected $fillable = [
        'nama_komoditas',
        'satuan',
        'harga_acuan',
        'kategori_id',
        'image_path',
        'icon',
        'is_active',
    ];

    protected $casts = [
        'harga_acuan' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'kategori_id');
    }

    public function harvestLogs(): HasMany
    {
        return $this->hasMany(HarvestLog::class, 'commodity_id');
    }
}
