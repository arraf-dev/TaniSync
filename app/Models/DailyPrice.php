<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyPrice extends Model
{
    protected $table = 'harga_bapok_harian';

    protected $fillable = [
        'id_pasar',
        'tanggal',
        'data_harga',
        'status',
        'created_by',
    ];

    protected $casts = [
        'data_harga' => 'array',
        'tanggal' => 'date',
    ];

    public function market(): BelongsTo
    {
        return $this->belongsTo(Market::class, 'id_pasar');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
