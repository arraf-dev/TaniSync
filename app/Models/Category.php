<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'kategori_komoditas';

    protected $fillable = [
        'nama_kategori',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function commodities(): HasMany
    {
        return $this->hasMany(Commodity::class, 'kategori_id');
    }
}
