<?php

namespace Database\Seeders;

use App\Models\Chat;
use App\Models\Customer;
use App\Models\Media;
use App\Models\Message;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Ticket;
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
        // initial permissions
        $this->call(PermissionSeeder::class);

        $this->seedAdminUser();

        $this->seedOperatorUser();

        $this->seedCustomerUser();
    }

    private function seedAdminUser(): void
    {
        $adminRole = $this->seedAdminRole();

        // create admin user
        $admin = User::factory()->create([
            'name' => 'Test Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('123456789')
        ]);

        // associate admin role to admin user
        $admin->role()->associate($adminRole);
        $admin->save();
    }

    private function seedAdminRole(): Role
    {
        // create admin role with all permissions
        $adminRole = Role::create(['name' => 'admin-role']);
        $adminRole->permissions()->sync(Permission::all());

        return $adminRole;
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

        $operator->role()->associate($this->seedOperatorRole());

        $operator->save();
    }

    private function seedOperatorRole(): Role
    {
        $operatorPermissions = Permission::where('model', Chat::class)
            ->orWhere('model', Customer::class)
            ->orWhere('model', Media::class)
            ->orWhere('model', Message::class)
            ->orWhere('model', Ticket::class)
            ->get()
            ->pluck("id")
            ->toArray();

        $role = Role::factory()->create(['name' => 'operator-role']);

        $role->permissions()->sync($operatorPermissions);

        return $role;
    }
}
