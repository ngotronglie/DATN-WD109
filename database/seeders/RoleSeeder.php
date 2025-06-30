<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = ['admin', 'user', 'manager'];
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }
    }
} 