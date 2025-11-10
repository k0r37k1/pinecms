<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Create roles for PineCMS
        Role::firstOrCreate(['name' => 'Administrator', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'Author', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'User', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'Guest', 'guard_name' => 'web']);
    }
}
