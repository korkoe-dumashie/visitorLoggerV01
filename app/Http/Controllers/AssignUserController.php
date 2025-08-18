<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\AssignUser;
use App\Mail\UpdateRole;
use App\Models\{Activities, Employee, Roles, User};
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Validation\Rules\Password as PasswordRules;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\{DB,Hash,Auth,Log,Mail,Password};
use Illuminate\Support\Str;

class AssignUserController extends Controller
{
    
    //display all users
    public function index(){
        $users = User::all();

        return view('users.index',compact('users'));
    }




    //add a new user
    public function create(){
        $employees = Employee::whereNotIn('email', function ($query) {
            $query->select('email')->from('users');
        })->get();

        $roles = Roles::get();


        return view('users.create',compact('employees','roles'));
    }


    public function newUser($username){
        return view('auth.reset-pwd',compact('username'));
    }


    public function newUserStore(){
        // dd(request()->all());
        $validated = request()->validate([
            'username'=>'required',
            'password'=>[
                'required',
                'confirmed',
                PasswordRules::min(8)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols()
            ],
        ]);

        Log::debug("Validated Data: " , $validated);


        $user = User::where('username',request()->username)->firstOrFail();


        $user->update([
            'password'=>Hash::make(request()->password),
        ]);


        return redirect('/')->with('success','Password updated successfully');
    }



    //store the user and send an email to reset password

    public function store(Request $request){
        $request->validate([
            'employee_id'=> 'required|exists:employees,id',
            'role_id'=> 'required|exists:roles,id',
        ]);

        
        Log::debug($request->all());        
        try{
            return DB::transaction(function () use ($request){
                $employee = Employee::findOrFail($request->employee_id);
                $role = Roles::findOrFail($request->role_id);
                // $token =Str::random(60);



                if($role->name !== 'security'){


                $user = User::create([
                    'name' => trim(implode(' ', [
                        $employee->first_name, 
                        $employee->other_name ?? '', 
                        $employee->last_name
                    ])),
                    'email' => $employee->email,
                    'role_id' => $request->input('role_id'),
                    'password' => Hash::make(Str::random(16)),
                    // 'password_reset_token' => $token,
                ]);
                



                $token = Password::createToken($user);

                Log::debug("new token" . $token);

                Log::debug("NEW USER: " . $user);
                Log::debug("Role Info : " . $role);

                Mail::to($user->email)->send(new AssignUser($user, $token));

                Log::debug('Mail Sent');

        $employee->update(['is_user' => true]);

        
        Activities::log(
            action: 'Added a new user',
            description: 'Assigned ' . $role->name . ' role to ' . $employee->first_name . ' ' . $employee->last_name
        );

        return redirect()->back()->with('success', 'User created successfully. An invitation email has been sent.');


                } else{


                    $username = Str::lower($employee->first_name . $employee->last_name) . mt_rand(1000, 9999);
                    $user = User::create([
                        'name' => trim(implode(' ', [
                            $employee->first_name, 
                            $employee->other_name ?? '', 
                            $employee->last_name
                        ])),
                        'username' => $username,
                        'role_id' => $request->input('role_id'),
                        'password' => Hash::make('NewSecurity@1234'),
                        // 'password_reset_token' => $token,
                    ]);

                    Log::debug("NEW USER: " . $user);

                    $employee->update(['is_user' => true]);

        
                    Activities::log(
                        action: 'Added a new user',
                        description: 'Assigned ' . $role->name . ' role to ' . $employee->first_name . ' ' . $employee->last_name
                    );
            
                    return redirect()->back()->with('success', 'Security User created successfully. ');

                }
        });
        }catch (\Exception $e){

            Log::error('User creation failed: ' . $e->getMessage());
            // return redirect('/')->with('error', 'An error occurred. Please try again later.');
        }
    }



    //display reset password form


    public function showResetForm(Request $request, $token){
        Log::debug("Token: " . $token);
        // dd($request->all());
        return view('auth.reset-password',[
            'token' => $token,
            'email'=> $request->email
        ]);
    }

    //reset password

    public function resetPassword(Request $request){

        // dd($request->all());
        $validated = $request->validate([
        'token'=>'required',
        'email' => 'required|email',
        'password'=>[
            'required',
            'confirmed',
            PasswordRules::min(8)
            ->letters()
            ->mixedCase()
            ->numbers()
            ->symbols()
        ],
        ]);
        Log::debug("Validated Data: " , $validated);

        $status = Password::reset(
            $request->only('email','password','password_confirmation','token'),
            function    ($user, $password){
                $user->forceFill([
                    'password'=>Hash::make($password),
                    // 'password_reset_token'=>null,
                ])->save();

                event(new PasswordReset($user));
            }
        );

        Log::debug("Status: " . $status);

        // return $status == Password::PASSWORD_RESET
        // ? redirect()
        // ->route('login')
        // ->with('status', __($status))
        // : back()->withErrors(['email' => [__($status)]]);

        return $status == Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            :   back()->withErrors(['email'=> [__($status)]]);
    }



    public function changeRole(User $user)
    {
        $roles = Roles::get();
    
        return view('users.update', compact('roles', 'user'));
    }
    public function modify(Request $request, User $user)
{
    // Log incoming request data
    Log::debug("Request Data:", $request->all());
    Log::debug("User Data:", ['user' => $user]);
    
    // Validate input
    $validated = $request->validate([
        'role' => 'required|exists:roles,id',
    ]);
    Log::debug("Role: " . $validated['role']);
    
    // Convert to same type and strict comparison
    if ((int)$user->role_id === (int)$validated['role']) {
        return response()->json([
            'success' => false,
            'message' => 'No changes made. User already has this role.',
        ]);
    }
    
    // Log validated data
    Log::debug("Validated Data:", $validated);
    
    // Update user role
    $user->update([
        'role_id' => $validated['role'],
    ]);

    $roleName = $user->role->name;


    Mail::to($user->email)->send(new UpdateRole($user,$roleName));

    Log::debug("Mail sent");
    
    // Get the updated role name (after update)
    
    // Log the activity
    Activities::log(
        action: 'Updated user role',
        description: Auth::user()->name . ' updated ' . $user->name . '\'s role to ' . $roleName
    );
    
    // Return a JSON success response
    return response()->json([
        'success' => true,
        'message' => $user->name . '\'s role updated successfully.'
    ]);
}
    public function destroy($id){
        try{
            $user = User::findOrFail($id);
            $user->delete();


            Activities::log(
                action: 'Deleted a user',
                description:    'Revoked ' . $user->name . '`s access to the platform'
            );



            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully'
            ], 200);
        }   catch(\Exception $e){
            return response()->json([
                'success'=>false,
                'error'=>'Failed to delete user'
            ],  500);
        }
    }

    
}
