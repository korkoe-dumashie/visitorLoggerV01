<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\{Activities, Module, Permission, Roles,User};
// use App\Models\User;
// use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB, Log};

class RolesController extends Controller
{
    public function index(){
        $roles =Roles::get();

        return view('roles.index',compact('roles'));
    }




    public function create(){
        return view('roles.create');
    }

    public function store(){
        request()->validate([
            'name'=>'required',
            'description'=>''
        ]);

        DB::beginTransaction();

        try{

        $role = Roles::create([
            'name'=>request('name'),
            'description'=>request('description')
        ]);
        $modules = Module::get();

        foreach($modules as $module){
            Permission::create([
                'role_id'=> $role->id,
                'module_id'=>$module->id,
                    'can_create' => false,
                    'can_view' => false,
                    'can_modify' => false,
                    'can_delete' => false,

            ]);
        }
                Activities::create([
                'user_id' => Auth::id(),
                'action' => 'Role creation',
                'description' => 'Created new role: ' . $role->name,
                // 'entity_type' => 'role',
                // 'entity_id' => $role->id,
                // 'metadata' => json_encode([
                //     'name' => $role->name,
                //     'description' => $role->description,
                // ])
            ]);
            DB::commit();
        return redirect()->route('users', ['tab' => 'roles'])->with('success', 'Role created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'An error occurred: ' . $e->getMessage())->withInput();
        }
    }

        public function edit($id)
    {
        $role = Roles::findOrFail($id);
        return view('roles.edit', compact('role'));
    }


    public function delete($id){
        // dd($id);
        try{


            $role = Roles::findOrFail($id);
            $role->delete();

            Log::debug('Role Deleted');

            Activities::log(
                action: 'Deleted ' . $role->name . ' role.',
                description: Auth::user()->name . ' deleted the ' . $role->name . ' role. All users under this role will be deleted as well.'
            );


        return response()->json([
            'success' => true,
            'message' => 'Role deleted successfully'
        ], 200);


        }catch(\Exception $e){
            Log::error('Error deleting role: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Unable to delete role.']);
        }
    }
}
