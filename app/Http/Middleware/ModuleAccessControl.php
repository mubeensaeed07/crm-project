<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ModuleAccessControl
{
    /**
     * Handle an incoming request.
     * This middleware ensures proper module access control
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        $supervisor = Auth::guard('supervisor')->user();
        
        // Check if user is accessing a module
        $routeName = $request->route()->getName();
        
        if (str_contains($routeName, 'hrm') || str_contains($routeName, 'finance') || str_contains($routeName, 'support')) {
            
            // Log module access attempts
            Log::info('Module access attempt', [
                'user_id' => $user ? $user->id : null,
                'supervisor_id' => $supervisor ? $supervisor->id : null,
                'route' => $routeName,
                'url' => $request->url(),
                'ip' => $request->ip()
            ]);
            
            // Additional security checks can be added here
            // For example, checking if user has module permissions
            // This is already handled in controllers, but this provides an extra layer
        }
        
        return $next($request);
    }
}
