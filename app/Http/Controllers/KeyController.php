<?php

namespace App\Http\Controllers;

use App\Models\Activities;
use App\Models\Employee;
use App\Models\Key;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class KeyController extends Controller
{
    public function create(){
        return view('keys.create-key');
    }

    //create a new key
    public function store(){
        request()->validate([
            'key_number'=>'required',
            'key_name'=>'required',
        ]);

        Key::create([
            'key_number'=>request('key_number'),
            'key_name'=>request('key_name'),
            'status'=>'active', // Default status when creating a key
        ]);

        Activities::log(
            action: 'Created New Key',
            description: 'New key with ID: ' . request('key_number')
        );

        session()->flash('success', 'Key created successfully');
        return redirect('/all_keys');
    }

    //show all keys 
    public function keys(){
        $keys = Key::get();
        return view('keys.index', compact('keys'));
    }

    // Activate a key
    public function activate($id){
        try {
            $key = Key::findOrFail($id);
            $key->update(['status' => 'active']);
            
            Activities::log(
                action: 'Activated a key',
                description: 'Activated the ' . $key->key_name . ' key'
            );
            
            session()->flash('success', 'Key activated successfully');
            
            return response()->json([
                'success' => true,
                'message' => 'Key activated successfully'
            ], 200);
            
        } catch(\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to activate key'
            ], 500);
        }
    }

    // Deactivate a key
    public function deactivate($id){
        try {
            $key = Key::findOrFail($id);
            $key->update(['status' => 'inactive']);
            
            Activities::log(
                action: 'Deactivated a key',
                description: 'Deactivated the ' . $key->key_name . ' key'
            );
            
            session()->flash('success', 'Key deactivated successfully');
            
            return response()->json([
                'success' => true,
                'message' => 'Key deactivated successfully'
            ], 200);
            
        } catch(\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to deactivate key'
            ], 500);
        }
    }
}