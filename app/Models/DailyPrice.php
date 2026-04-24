<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyPrice extends Model
{
    protected $casts = [
        'data_harga' => 'array', // Automatically turns JSON into a PHP array
        'tanggal' => 'date',
    ];
}
