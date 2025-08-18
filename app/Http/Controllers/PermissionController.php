<?php

namespace App\Http\Controllers;

use App\Models\{Module,Permission,Roles};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PermissionController extends Controller
{
    //
    public function create(){
        $roles = Roles::all();
        return view('permissions.create',compact('roles'));
    }


    
    public function store(Request $request)
    {
        $request->validate([
            'role' => 'required|exists:roles,id',
        ]);

        // Get the selected role
        $roleId = $request->input('role');

        // Module categories from the form
        $moduleCategories = [
            'visits',
            'logs',
            'staff',
            'keys',
            'settings',
            'reports',
            'departments',
            'roles',
            'user'
        ];

        try {
            DB::beginTransaction();

            foreach ($moduleCategories as $category) {
                // Skip if no permissions were selected for this category
                if (!$request->has($category)) {
                    continue;
                }

                // Get or create the module
                $module = Module::firstOrCreate(['name' => str_singular($category)]);

                // Default permission values
                $canView = false;
                $canCreate = false;
                $canModify = false;
                $canDelete = false;

                // Check which permissions were selected
                $permissions = $request->input($category, []);
                foreach ($permissions as $permission) {
                    if (strpos($permission, 'view_') !== false) {
                        $canView = true;
                    }
                    if (strpos($permission, 'create_') !== false) {
                        $canCreate = true;
                    }
                    if (strpos($permission, 'modify_') !== false) {
                        $canModify = true;
                    }
                    if (strpos($permission, 'delete_') !== false) {
                        $canDelete = true;
                    }
                }

                // Create or update permission record
                Permission::updateOrCreate(
                    [
                        'role_id' => $roleId,
                        'module_id' => $module->id,
                    ],
                    [
                        'can_view' => $canView,
                        'can_create' => $canCreate,
                        'can_modify' => $canModify,
                        'can_delete' => $canDelete,
                    ]
                );
            }

            DB::commit();

            return redirect()->route('roles.permissions', $roleId)
                ->with('success', 'Permissions updated successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error updating permissions: ' . $e->getMessage());
        }
    }
}
