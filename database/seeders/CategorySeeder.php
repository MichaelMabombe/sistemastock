<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Alimentos'],
            ['name' => 'Materiais'],
            ['name' => 'Equipamentos'],
            ['name' => 'Pecas'],
        ];

        foreach ($categories as $category) {
            Category::query()->firstOrCreate(['name' => $category['name']], $category);
        }
    }
}
