<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $table = 'kategori_komoditas';

    protected $fillable = [
        'nama_kategori',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function commodities(): HasMany
    {
        return $this->hasMany(Commodity::class, 'kategori_id');
    }
}
