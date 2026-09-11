<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // initial permissions
        $this->call(PermissionSeeder::class);

        // create admin role with all permissions
        $adminRole = Role::create(['name' => 'admin-role']);
        $adminRole->permissions()->sync(Permission::all());

        // create admin user
        $admin = User::factory()->create([
            'name' => 'Test Admin',
            'email' => 'admin@example.com',
            'password'  => Hash::make('123456789')
        ]);

        // associate admin role to admin user
        $admin->role()->associate($adminRole);
        $admin->save();

        // create customer user for example
        User::factory()->customer()->create([
            'name' => 'Test Customer',
            'email' => 'customer@example.com',
            'password'  => Hash::make('123456789')
        ]);

        // create operator user for example
        User::factory()->operator()->create([
            'name' => 'Test Operator',
            'email' => 'operator@example.com',
            'password'  => Hash::make('123456789')
        ]);
    }
}
