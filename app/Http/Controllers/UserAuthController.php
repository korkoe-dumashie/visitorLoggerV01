<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\{Activities,User, UserSessions};
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth,Log,Session};
use Illuminate\Validation\ValidationException;

class UserAuthController extends Controller
{
    public function login(Request $request)
    {
        // Validate basic required fields
        $request->validate([
            'email' => 'required',  // This can be email or username
            'password' => 'required'
        ]);

        $login = $request->input('email');
        
        // Find user by email or username
        $user = User::where('email', $login)
                    ->orWhere('username', $login)
                    ->first();
        
        // Check if user exists
        if (!$user) {
            return redirect()->back()->withErrors(['email' => 'Invalid login credentials']);
        }
        
        // Check for default password scenario
        if ($request->password == 'NewSecurity@1234') {
            return redirect("/newUser/{$user->username}")
                ->withErrors([
                    'password' => 'Your password is set to default. Please create a new password'
                ]);
        }
        
        // Try authentication with email
        $emailAuth = Auth::attempt([
            'email' => $user->email,
            'password' => $request->password
        ]);
        
        // Try authentication with username if email auth fails
        $usernameAuth = !$emailAuth ? Auth::attempt([
            'username' => $user->username,
            'password' => $request->password
        ]) : false;
        
        // If both authentication methods fail
        if (!$emailAuth && !$usernameAuth) {
            throw ValidationException::withMessages([
                'email' => 'Wrong credentials. Please try again'
            ]);
        }
        
        // Regenerate session
        $request->session()->regenerate();

        UserSessions::create([
            'user_id' => $user->id,
            'guard' => Auth::getDefaultDriver(),
            'session_id' => Session::getId(),
            'last_activity' => Carbon::now()
        ]);

        Log::info('user Logged in,'. $user->name);
        
        // Log the activity
        Activities::log(
            action: 'login',
            description: $user->name . ' logged in'
        );
        
        // Redirect to dashboard with welcome message
        return redirect('/')->with(
            'success',
            'Welcome back, ' . $user->name
        );
    }
    
    public function logout(Request $request)
    {
        // Log the logout activity if user is authenticated
        if (Auth::check()) {
            $user = Auth::user();

            // Update session logout time
            UserSessions::where('session_id', Session::getId())
                ->update(['logged_out_at' => Carbon::now()]);

            Activities::log('logout', $user->name . ' logged out');
        }
        
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('login');
    }
}