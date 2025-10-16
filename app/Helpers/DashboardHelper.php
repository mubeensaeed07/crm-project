<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class DashboardHelper
{
    /**
     * Get the appropriate dashboard route based on user role
     * 
     * @return string
     */
    public static function getDashboardRoute()
    {
        // Check supervisor guard first
        $supervisor = Auth::guard('supervisor')->user();
        if ($supervisor) {
            return 'supervisor.dashboard';
        }
        
        // Check web guard
        $user = Auth::user();
        if (!$user) {
            return 'login';
        }
        
        switch ($user->role_id) {
            case 1: // SuperAdmin
                return 'superadmin.dashboard';
            case 2: // Admin
                return 'admin.dashboard';
            case 3: // User
                return 'user.dashboard';
            default:
                return 'user.dashboard'; // Default fallback
        }
    }
    
    /**
     * Get the dashboard route name for display purposes
     * 
     * @return string
     */
    public static function getDashboardLabel()
    {
        // Check supervisor guard first
        $supervisor = Auth::guard('supervisor')->user();
        if ($supervisor) {
            return 'Supervisor Dashboard';
        }
        
        // Check web guard
        $user = Auth::user();
        if (!$user) {
            return 'Login';
        }
        
        switch ($user->role_id) {
            case 1: // SuperAdmin
                return 'SuperAdmin Dashboard';
            case 2: // Admin
                return 'Admin Dashboard';
            case 3: // User
                return 'User Dashboard';
            default:
                return 'Dashboard'; // Default fallback
        }
    }
    
    /**
     * Get the module dashboard route based on module ID
     * 
     * @param int $moduleId
     * @return string
     */
    public static function getModuleDashboardRoute($moduleId)
    {
        switch ($moduleId) {
            case 1: // HRM
                return route('hrm.dashboard');
            case 2: // Finance
                return route('finance.dashboard');
            case 3: // Support
                return route('support.dashboard');
            default:
                return route('user.dashboard');
        }
    }
}
