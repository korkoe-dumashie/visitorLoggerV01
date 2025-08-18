<?php

namespace App\Http\Middleware;

use App\Models\Roles;
use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CheckModulePermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $module, $action): Response
    {
        $user = Auth::user(); // Get the authenticated user

        // dd('Logged In');
        $role_id = $user->role_id;


        if (!$role_id || !Roles::hasPermission($role_id,  $module, $action)) {
            abort(403, 'Go back! Now!');
        }
    
        return $next($request);
    }

    
}
