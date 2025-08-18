<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\Permission;
use App\Models\Roles;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = Roles::all();
        $modules = Module::all();

        $admin = $roles[0];
        $hr = $roles[1];
        $security = $roles[2];
        $support = $roles[3];
        $visitor = $roles[4];

        $staff = $modules[0];
        $keys = $modules[1];
        $settings = $modules[2];
        $logs = $modules[3];
        $visits = $modules[4];
        $user_roles = $modules[5];
        $user = $modules[6];
        $reports = $modules[7];
        $departments = $modules[8];


        //permissions for admin staff

        //1
        Permission::create([
            'role_id'=>$admin->id,
            'module_id'=>$staff->id,
            'can_view'=>1,
            'can_create'=>1,
            'can_modify'=>1,
            'can_delete'=>1
        ]);


        //2
        Permission::create([
            'role_id'=>$admin->id,
            'module_id'=>$keys->id,
            'can_view'=>1,
            'can_create'=>1,
            'can_modify'=>1,
            'can_delete'=>1
        ]);

        //3
        Permission::create([
            'role_id'=>$admin->id,
            'module_id'=>$settings->id,
            'can_view'=>1,
            'can_create'=>1,
            'can_modify'=>1,
            'can_delete'=>1
        ]);


        //4
        Permission::create([
            'role_id'=>$admin->id,
            'module_id'=>$logs->id,
            'can_view'=>1,
            'can_create'=>1,
            'can_modify'=>1,
            'can_delete'=>1
        ]);


        //5

        Permission::create([
            'role_id'=>$admin->id,
            'module_id'=>$visits->id,
            'can_view'=>1,
            'can_create'=>1,
            'can_modify'=>1,
            'can_delete'=>1
        ]);

        //6

        Permission::create([
            'role_id'=>$admin->id,
            'module_id'=>$user_roles->id,
            'can_view'=>1,
            'can_create'=>1,
            'can_modify'=>1,
            'can_delete'=>1
        ]);


        //7
        Permission::create([
            'role_id'=>$admin->id,
            'module_id'=>$user->id,
            'can_view'=>1,
            'can_create'=>1,
            'can_modify'=>1,
            'can_delete'=>1
        ]);


        //8

        Permission::create([
            'role_id'=>$admin->id,
            'module_id'=>$reports->id,
            'can_view'=>1,
            'can_create'=>1,
            'can_modify'=>1,
            'can_delete'=>1
        ]);

        //9

        Permission::create([
            'role_id'=>$admin->id,
            'module_id'=>$departments->id,
            'can_view'=>1,
            'can_create'=>1,
            'can_modify'=>1,
            'can_delete'=>1
        ]);


        //permissions for hr staff

        //1

        Permission::create([
            'role_id'=>$hr->id,
            'module_id'=>$staff->id,
            'can_view'=>1,
            'can_create'=>0,
            'can_modify'=>0,
            'can_delete'=>0
        ]);


        //2
        Permission::create([
            'role_id'=>$hr->id,
            'module_id'=>$keys->id,
            'can_view'=>1,
            'can_create'=>0,
            'can_modify'=>0,
            'can_delete'=>0,
        ]);


        //3

        Permission::create([
            'role_id'=>$hr->id,
            'module_id'=>$settings->id,
            'can_view'=>0,
            'can_create'=>0,
            'can_modify'=>0,
            'can_delete'=>0
        ]);


        //4

        Permission::create([
            'role_id'=>$hr->id,
            'module_id'=>$logs->id,
            'can_view'=>0,
            'can_create'=>0,
            'can_modify'=>0,
            'can_delete'=>0
        ]);

        //5

        Permission::create([
            'role_id'=>$hr->id,
            'module_id'=>$visits->id,
            'can_view'=>1,
            'can_create'=>1,
            'can_modify'=>1,
            'can_delete'=>0
        ]);


        //6
        Permission::create([
            'role_id'=>$hr->id,
            'module_id'=>$user_roles->id,
            'can_view'=>0,
            'can_create'=>0,
            'can_modify'=>0,
            'can_delete'=>0
        ]);


        //7
        Permission::create([
            'role_id'=>$hr->id,
            'module_id'=>$user->id,
            'can_view'=>0,
            'can_create'=>0,
            'can_modify'=>0,
            'can_delete'=>0
        ]);


        //8
        Permission::create([
            'role_id'=>$hr->id,
            'module_id'=>$reports->id,
            'can_view'=>1,
            'can_create'=>0,
            'can_modify'=>0,
            'can_delete'=>0
        ]);


        //9
        Permission::create([
            'role_id'=>$hr->id,
            'module_id'=>$departments->id,
            'can_view'=>1,
            'can_create'=>0,
            'can_modify'=>0,
            'can_delete'=>0
        ]);


        //permissions for security staff

        //1
        Permission::create([
            'role_id'=>$security->id,
            'module_id'=>$staff->id,
            'can_view'=>0,
            'can_create'=>0,
            'can_modify'=>0,
            'can_delete'=>0
        ]);


        //2

        Permission::create([
            'role_id'=>$security->id,
            'module_id'=>$keys->id,
            'can_view'=>1,
            'can_create'=>0,
            'can_modify'=>0,
            'can_delete'=>0,
        ]);


        //3

        Permission::create([
            'role_id'=>$security->id,
            'module_id'=>$settings->id,
            'can_view'=>0,
            'can_create'=>0,
            'can_modify'=>0,
            'can_delete'=>0
        ]);


        //4
        Permission::create([
            'role_id'=>$security->id,
            'module_id'=>$logs->id,
            'can_view'=>0,
            'can_create'=>0,
            'can_modify'=>0,
            'can_delete'=>0
        ]);

        //5

        Permission::create([
            'role_id'=>$security->id,
            'module_id'=>$visits->id,
            'can_view'=>1,
            'can_create'=>1,
            'can_modify'=>1,
            'can_delete'=>0
        ]);

        //6

        Permission::create([
            'role_id'=>$security->id,
            'module_id'=>$user_roles->id,
            'can_view'=>0,
            'can_create'=>0,
            'can_modify'=>0,
            'can_delete'=>0
        ]);


        //7
        Permission::create([
            'role_id'=>$security->id,
            'module_id'=>$user->id,
            'can_view'=>0,
            'can_create'=>0,
            'can_modify'=>0,
            'can_delete'=>0
        ]);


        //8
        Permission::create([
            'role_id'=>$security->id,
            'module_id'=>$reports->id,
            'can_view'=>0,
            'can_create'=>0,
            'can_modify'=>0,
            'can_delete'=>0
        ]);

        //9

        Permission::create([
            'role_id'=>$security->id,
            'module_id'=>$departments->id,
            'can_view'=>0,
            'can_create'=>0,
            'can_modify'=>0,
            'can_delete'=>0
        ]);


        //permissions for support staff


        //1

        Permission::create([
            'role_id'=>$support->id,
            'module_id'=>$staff->id,
            'can_view'=>1,
            'can_create'=>0,
            'can_modify'=>0,
            'can_delete'=>0
        ]);

        //2

        Permission::create([
            'role_id'=>$support->id,
            'module_id'=>$keys->id,
            'can_view'=>1,
            'can_create'=>0,
            'can_modify'=>0,
            'can_delete'=>0,
        ]);

        //3

        Permission::create([
            'role_id'=>$support->id,
            'module_id'=>$settings->id,
            'can_view'=>0,
            'can_create'=>0,
            'can_modify'=>0,
            'can_delete'=>0
        ]);


        //4
        Permission::create([
            'role_id'=>$support->id,
            'module_id'=>$logs->id,
            'can_view'=>1,
            'can_create'=>1,
            'can_modify'=>0,
            'can_delete'=>0
        ]);

        //5

        Permission::create([
            'role_id'=>$support->id,
            'module_id'=>$visits->id,
            'can_view'=>1,
            'can_create'=>0,
            'can_modify'=>0,
            'can_delete'=>0
        ]);


        //6
        Permission::create([
            'role_id'=>$support->id,
            'module_id'=>$user_roles->id,
            'can_view'=>1,
            'can_create'=>0,
            'can_modify'=>0,
            'can_delete'=>0
        ]);


        //7
        Permission::create([
            'role_id'=>$support->id,
            'module_id'=>$user->id,
            'can_view'=>1,
            'can_create'=>0,
            'can_modify'=>0,
            'can_delete'=>0
        ]);


        //8

        Permission::create([
            'role_id'=>$support->id,
            'module_id'=>$reports->id,
            'can_view'=>1,
            'can_create'=>0,
            'can_modify'=>0,
            'can_delete'=>0
        ]);


        //9
        Permission::create([
            'role_id'=>$support->id,
            'module_id'=>$departments->id,
            'can_view'=>1,
            'can_create'=>0,
            'can_modify'=>0,
            'can_delete'=>0
        ]);


        //permissions for visitors


        //1

        Permission::create([
            'role_id'=>$visitor->id,
            'module_id'=>$staff->id,
            'can_view'=>0,
            'can_create'=>0,
            'can_modify'=>0,
            'can_delete'=>0
        ]);

        //2

        Permission::create([
            'role_id'=>$visitor->id,
            'module_id'=>$keys->id,
            'can_view'=>0,
            'can_create'=>0,
            'can_modify'=>0,
            'can_delete'=>0,
        ]);

        //3

        Permission::create([
            'role_id'=>$visitor->id,
            'module_id'=>$settings->id,
            'can_view'=>0,
            'can_create'=>0,
            'can_modify'=>0,
            'can_delete'=>0
        ]);


        //4
        Permission::create([
            'role_id'=>$visitor->id,
            'module_id'=>$logs->id,
            'can_view'=>0,
            'can_create'=>0,
            'can_modify'=>0,
            'can_delete'=>0
        ]);

        //5

        Permission::create([
            'role_id'=>$visitor->id,
            'module_id'=>$visits->id,
            'can_view'=>1,
            'can_create'=>1,
            'can_modify'=>0,
            'can_delete'=>0
        ]);


        //6
        Permission::create([
            'role_id'=>$visitor->id,
            'module_id'=>$user_roles->id,
            'can_view'=>0,
            'can_create'=>0,
            'can_modify'=>0,
            'can_delete'=>0
        ]);


        //7
        Permission::create([
            'role_id'=>$visitor->id,
            'module_id'=>$user->id,
            'can_view'=>0,
            'can_create'=>0,
            'can_modify'=>0,
            'can_delete'=>0
        ]);


        //8

        Permission::create([
            'role_id'=>$visitor->id,
            'module_id'=>$reports->id,
            'can_view'=>0,
            'can_create'=>0,
            'can_modify'=>0,
            'can_delete'=>0
        ]);


        //9
        Permission::create([
            'role_id'=>$visitor->id,
            'module_id'=>$departments->id,
            'can_view'=>0,
            'can_create'=>0,
            'can_modify'=>0,
            'can_delete'=>0
        ]);


    }
}
