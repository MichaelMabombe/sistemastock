<?php

namespace Database\Seeders;

use App\Models\Warehouse;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $warehouses = [
            [
                'name' => 'Armazem Principal',
                'code' => 'ARM-001',
                'city' => 'Maputo',
                'address' => 'Sede',
            ],
            [
                'name' => 'Filial Matola',
                'code' => 'ARM-002',
                'city' => 'Matola',
                'address' => 'Filial',
            ],
        ];

        foreach ($warehouses as $warehouse) {
            Warehouse::query()->firstOrCreate(['code' => $warehouse['code']], $warehouse);
        }
    }
}
