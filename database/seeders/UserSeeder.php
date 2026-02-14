<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $warehouse = Warehouse::query()->first();

        User::query()->updateOrCreate(
            ['email' => 'admin@sistemastock.test'],
            [
                'name' => 'Admin Sistema',
                'password' => Hash::make('admin123'),
                'role' => UserRole::ADMIN,
                'warehouse_id' => $warehouse?->id,
                'is_active' => true,
            ]
        );
    }
}
