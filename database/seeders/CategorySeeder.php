<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = ['Beras & Karbohidrat', 'Sayuran', 'Bumbu Dapur', 'Protein Hewani', 'Minyak & Gula'];
        
        foreach ($categories as $cat) {
            \App\Models\Category::create(['nama_kategori' => $cat]);
        }
    }
}
