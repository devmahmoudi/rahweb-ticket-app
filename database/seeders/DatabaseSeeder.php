<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Workgroup;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->seedSuperadminUser();

        $this->seedOperatorUser();

        $this->seedCustomerUser();
    }

    private function seedSuperadminUser(): User
    {
        return User::factory()->superadmin()->create([
            'name' => 'Test Superadmin',
            'email' => 'superadmin@example.com',
            'password' => Hash::make('123456789'),
        ]);
    }

    private function seedCustomerUser(): User
    {
        // create customer user for example
        return User::factory()->customer()->create([
            'name' => 'Test Customer',
            'email' => 'customer@example.com',
            'password' => Hash::make('123456789')
        ]);
    }

    private function seedOperatorUser(): User
    {
        $workgroup = $this->seedWorkgroup();

        // create operator user for example
        $operator = User::factory()->operator()->create([
            'name' => 'Test Operator',
            'email' => 'operator@example.com',
            'password' => Hash::make('123456789')
        ]);

        $operator->workgroups()->attach($workgroup);

        return $operator;
    }

    private function seedWorkgroup():Workgroup
    {
        return Workgroup::factory()->create(['name' => 'پشتیبانی']);
    }
}
