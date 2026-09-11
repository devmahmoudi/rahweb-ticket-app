<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Workgroup;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WorkgroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $workgroups = Workgroup::factory()->count(15)->create();

        foreach ($workgroups as $workgroup)
            $workgroup->users()->attach(User::factory()->operator()->create());
    }
}
