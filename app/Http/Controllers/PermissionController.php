<?php

namespace App\Http\Controllers;

use App\Models\{Module, Permission, Roles, Activities};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Auth, Log};

class PermissionController extends Controller
{

        public function permissions( $id)
    {

        $role = Roles::findOrFail($id);
        $modules = Module::all();

        // Load existing permissions
        $permissions = Permission::where('role_id', $id)->get()->keyBy('module_id');

        return view('permissions.index', compact('role', 'modules', 'permissions'));
    }
    /**
     * Get permissions for a specific role (API endpoint if needed)
     */
    // public function getPermissions($roleId)
    // {

    //     // dd($roleId);
    //     $role = Roles::with('modules')->findOrFail($roleId);

    //     $formattedPermissions = [];
    //     foreach ($role->permissions as $permission) {
    //         $formattedPermissions[$permission->module->name] = [
    //             'can_create' => $permission->can_create,
    //             'can_view' => $permission->can_view,
    //             'can_modify' => $permission->can_modify,
    //             'can_delete' => $permission->can_delete,
    //         ];
    //     }

    //     return response()->json([
    //         'role' => $role->name,
    //         'permissions' => $formattedPermissions
    //     ]);
    // }

    public function getPermissions($roleId, Request $request)
{
    $request->validate([
        'permissions' => 'required|array',
    ]);

    DB::beginTransaction();
    try {
        $role = Roles::findOrFail($roleId);
        $permissionsData = $request->input('permissions');

        // First, get all module IDs to detach permissions not in the form
        $moduleIds = array_keys($permissionsData);

        // Sync permissions for each module
        foreach ($permissionsData as $moduleId => $perms) {
            DB::table('permissions')
                ->updateOrInsert(
                    [
                        'role_id' => $roleId,
                        'module_id' => $moduleId
                    ],
                    [
                        'can_create' => isset($perms['can_create']) ? 1 : 0,
                        'can_view' => isset($perms['can_view']) ? 1 : 0,
                        'can_modify' => isset($perms['can_modify']) ? 1 : 0,
                        'can_delete' => isset($perms['can_delete']) ? 1 : 0,
                        'updated_at' => now(),
                        'created_at' => DB::raw('COALESCE(created_at, NOW())')
                    ]
                );
        }

        Activities::create([
            'user_id' => Auth::id(),
            'action' => 'Permissions update',
            'description' => "Updated permissions for role: {$role->name}",
            // 'entity_type' => 'permission',
            // 'entity_id' => $roleId,
            // 'metadata' => json_encode(['role' => $role->name])
        ]);

        DB::commit();

        return redirect()->route('users', ['tab' => 'roles'])
            ->with('success', 'Permissions updated successfully.');

    } catch (\Exception $e) {

        Log::debug('Error updating permissions: ' . $e->getMessage());
        DB::rollBack();
        return back()->with('error', 'An error occurred while updating permission ');
    }
}

    /**
     * Bulk update permissions for multiple roles
     */
    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
            'module_id' => 'required|exists:modules,id',
            'permissions' => 'required|array',
        ]);

        DB::beginTransaction();
        try {
            $roleIds = $request->input('roles');
            $moduleId = $request->input('module_id');
            $permissions = $request->input('permissions');

            foreach ($roleIds as $roleId) {
                Permission::updateOrCreate(
                    [
                        'role_id' => $roleId,
                        'module_id' => $moduleId
                    ],
                    [
                        'can_create' => $permissions['can_create'] ?? false,
                        'can_view' => $permissions['can_view'] ?? false,
                        'can_modify' => $permissions['can_modify'] ?? false,
                        'can_delete' => $permissions['can_delete'] ?? false,
                    ]
                );
            }

            // Log the activity
            Activities::create([
                'user_id' => Auth::id(),
                'action_type' => 'bulk_update',
                'description' => 'Bulk updated permissions for ' . count($roleIds) . ' roles',
                'entity_type' => 'permission',
                'entity_id' => null,
                'metadata' => json_encode([
                    'roles' => $roleIds,
                    'module_id' => $moduleId
                ])
            ]);

            DB::commit();

            return back()->with('success', 'Permissions updated successfully for selected roles.');

        } catch (\Exception $e) {
            Log::debug('Error in bulk permission update: ' . $e->getMessage());
            DB::rollBack();
            return back()->with('error', 'An error occurred please try again.');
        }
    }

    /**
     * Clone permissions from one role to another
     */
    public function clonePermissions(Request $request)
    {
        $request->validate([
            'source_role_id' => 'required|exists:roles,id',
            'target_role_id' => 'required|exists:roles,id|different:source_role_id',
        ]);

        DB::beginTransaction();
        try {
            $sourceRoleId = $request->input('source_role_id');
            $targetRoleId = $request->input('target_role_id');

            $sourcePermissions = Permission::where('role_id', $sourceRoleId)->get();

            foreach ($sourcePermissions as $sourcePermission) {
                Permission::updateOrCreate(
                    [
                        'role_id' => $targetRoleId,
                        'module_id' => $sourcePermission->module_id
                    ],
                    [
                        'can_create' => $sourcePermission->can_create,
                        'can_view' => $sourcePermission->can_view,
                        'can_modify' => $sourcePermission->can_modify,
                        'can_delete' => $sourcePermission->can_delete,
                    ]
                );
            }

            $sourceRole = Roles::find($sourceRoleId);
            $targetRole = Roles::find($targetRoleId);

            // Log the activity
            Activities::create([
                'user_id' => Auth::id(),
                'action_type' => 'clone',
                'description' => "Cloned permissions from {$sourceRole->name} to {$targetRole->name}",
                'entity_type' => 'permission',
                'entity_id' => null,
                'metadata' => json_encode([
                    'source_role' => $sourceRole->name,
                    'target_role' => $targetRole->name
                ])
            ]);

            DB::commit();

            return back()->with('success', "Permissions cloned successfully from {$sourceRole->name} to {$targetRole->name}.");

        } catch (\Exception $e) {
            Log::debug('Error cloning permissions: ' . $e->getMessage());
            DB::rollBack();
            return back()->with('error', 'An error occurred please try again.');
        }
    }
}
