<?php

namespace Database\Seeders;

use App\Enums\Permission\BasicPermission;
use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $models = glob(app_path('/Models') . DIRECTORY_SEPARATOR . '*.php');

        $basicPermissions = array_values(BasicPermission::cases());

        foreach ($models as $model)
            foreach ($basicPermissions as $permission){
                $row = [
                    'name' => $permission->value,
                    'model' => 'App\Models\\' . pathinfo($model, PATHINFO_FILENAME)
                ];

                if(! Permission::where($row)->exists())
                    Permission::create($row);
            }
    }
}
