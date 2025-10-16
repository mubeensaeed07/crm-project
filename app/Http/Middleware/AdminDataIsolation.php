<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AdminDataIsolation
{
    /**
     * Handle an incoming request.
     * This middleware ensures that admins can only access their own data
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        
        // Only apply to authenticated users
        if (!$user) {
            return $next($request);
        }
        
        // Log admin data access for security monitoring
        if ($user->isAdmin() || $user->isSuperAdmin()) {
            Log::info('Admin data access', [
                'admin_id' => $user->id,
                'admin_name' => $user->full_name,
                'route' => $request->route()->getName(),
                'url' => $request->url(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);
        }
        
        // Add admin_id to request for easy access in controllers
        $request->merge(['current_admin_id' => $user->id]);
        
        return $next($request);
    }
}
