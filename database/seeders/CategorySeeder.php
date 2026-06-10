<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = ['Pangan', 'Hortikultura', 'Umbi-umbian', 'Palawija'];

        foreach ($categories as $cat) {
            Category::query()->updateOrCreate(
                ['nama_kategori' => $cat],
                ['is_active' => true]
            );
        }
    }
}
