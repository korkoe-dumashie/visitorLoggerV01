<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\{Activities, Roles,User};
// use App\Models\User;
// use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Log};

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

        Roles::create([
            'name'=>request('name'),
            'description'=>request('description')
        ]);

        return redirect('/roles');
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
