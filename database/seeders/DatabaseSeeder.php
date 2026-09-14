<?php

namespace Database\Seeders;

use App\Models\User;
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

    private function seedSuperadminUser(): void
    {
        User::factory()->superadmin()->create([
            'name' => 'Test Superadmin',
            'email' => 'superadmin@example.com',
            'password' => Hash::make('123456789'),
        ]);
    }

    private function seedCustomerUser(): void
    {
        // create customer user for example
        User::factory()->customer()->create([
            'name' => 'Test Customer',
            'email' => 'customer@example.com',
            'password' => Hash::make('123456789')
        ]);
    }

    private function seedOperatorUser(): void
    {
        // create operator user for example
        $operator = User::factory()->operator()->create([
            'name' => 'Test Operator',
            'email' => 'operator@example.com',
            'password' => Hash::make('123456789')
        ]);

    }
}
