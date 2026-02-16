<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\AssignUser;
use App\Mail\UpdateRole;
use App\Models\{Activities, Employee, Module, Roles, User};
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Validation\Rules\Password as PasswordRules;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\{DB,Hash,Auth,Log,Mail,Password};
use Illuminate\Support\Str;

use function Laravel\Prompts\select;

class AssignUserController extends Controller
{



    public function index(Request $request)
    {

        $users = User::with('role')->get();
        $activeTab = $request->get('tab', 'users');

        if ($activeTab === 'users') {
            $query = User::with('role');

            // Search functionality
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('username', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhereHas('role', function($roleQuery) use ($search) {
                          $roleQuery->where('name', 'like', "%{$search}%");
                      });
                });
            }

            // Role filter
            if ($request->filled('role') && $request->role !== 'all') {
                $query?->where('role_id', $request->role);
            }

            $users = $query->paginate(10)->appends($request->all());
            $roles = Roles::with('user')->get();

            return view('users.index', compact('users', 'roles', 'activeTab'));
        } else {
            $query = Roles::with('user');


            // Search functionality
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
                });
            }
            // $roles = $query->paginate(10)->appends($request->all());
            $roles = Roles::with('user')->get();
            $modules = Module::with('role')->get();
            // dd($roles);

            return view('users.index', compact('roles', 'modules', 'activeTab','users'));
        }
    }



    //add a new user
    public function create(){

    $employees = Employee::where('is_user',false)->get();
    // dd($employees);
        // dd($employees);
        // Log::debug($employees);
        $roles = Roles::get();


        return view('users.create',compact('employees','roles'));
    }


    public function newUser($username){
        return view('auth.reset-pwd',compact('username'));
    }


    public function newUserStore(){
        dd(request()->all());
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



public function store(Request $request)
{
    $request->validate([
        'employee_id' => 'required|exists:employees,id',
        'role_id' => 'required|exists:roles,id',
    ]);

    try {
        return DB::transaction(function () use ($request) {

            $employee = Employee::findOrFail($request->employee_id);
            $role = Roles::findOrFail($request->role_id);

            $fullName = trim(implode(' ', array_filter([
                $employee->first_name,
                $employee->other_name,
                $employee->last_name
            ])));

            $isSecurity = $role->name === 'security';

            $userData = [
                'name' => $fullName,
                'role_id' => $request->role_id,
            ];

            // Create user
            if ($isSecurity) {
                $user = $this->createSecurityUser($userData, $employee);
                $message = 'Security User created successfully.';
                $redirectUrl = url()->previous();
            } else {
                $user = $this->createRegularUser($userData, $employee);
                $message = 'User created successfully. An invitation email has been sent.';
                $redirectUrl = url('/users');
            }

            // Update employee
            $employee->update(['is_user' => true]);

            // Log activity (NOW always runs)
            Activities::log(
                action: 'Added a new user',
                description: "Assigned {$role->name} role to {$employee->first_name} {$employee->last_name}"
            );

            // Single exit point
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'redirect' => $redirectUrl
                ]);
            }

            return redirect($redirectUrl)->with('success', $message);
        });

    } catch (\Exception $e) {
        Log::error('User creation failed: ' . $e->getMessage());
        return redirect()->back()
            ->with('error', 'An error occurred. Please contact support for assistance.');
    }
}

private function createSecurityUser(array $userData, Employee $employee): User
{
    $username = Str::lower($employee->first_name . $employee->last_name) . mt_rand(1000, 9999);

    return User::create(array_merge($userData, [
        'username' => $username,
        'password' => Hash::make('NewSecurity@1234'),
    ]));
}

private function createRegularUser(array $userData, Employee $employee): User
{
    $user = User::create(array_merge($userData, [
        'email' => $employee->email,
        'password' => Hash::make(Str::random(16)),
    ]));

    Log::debug("User created: ", ['user_id' => $user->id, 'email' => $user->email]);


    $token = Password::createToken($user);

    Mail::to($user->email)->send(new AssignUser($user, $token));

    Log::info("User created and invitation sent", [
        'user_id' => $user->id,
        'email' => $user->email
    ]);

    return $user;
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

            $employee = Employee::where('email',$user->email)->first();
            $user->forceDelete();

            if($employee){
                $employee->update(['is_user'=>false]);
            }



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
