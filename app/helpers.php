<?php

if (!function_exists('getDashboardRoute')) {
    /**
     * Get the appropriate dashboard route based on user role
     * 
     * @return string
     */
    function getDashboardRoute()
    {
        $user = auth()->user();
        
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
            case 4: // Supervisor
                return 'supervisor.dashboard';
            default:
                return 'user.dashboard'; // Default fallback
        }
    }
}

if (!function_exists('getDashboardLabel')) {
    /**
     * Get the dashboard route name for display purposes
     * 
     * @return string
     */
    function getDashboardLabel()
    {
        $user = auth()->user();
        
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
            case 4: // Supervisor
                return 'Supervisor Dashboard';
            default:
                return 'Dashboard'; // Default fallback
        }
    }
}

if (!function_exists('canCreateUsers')) {
    /**
     * Check if current user can create other users
     * Only SuperAdmin, Admin, and Supervisor can create users
     * 
     * @return bool
     */
    function canCreateUsers()
    {
        $user = auth()->user();
        
        if (!$user) {
            return false;
        }
        
        // Only SuperAdmin (1), Admin (2), and Supervisor (4) can create users
        return in_array($user->role_id, [1, 2, 4]);
    }
}

if (!function_exists('hasPermission')) {
    /**
     * Check if current user has specific permission for a module
     * 
     * @param int $moduleId
     * @param string $permission
     * @return bool
     */
    function hasPermission($moduleId, $permission)
    {
        // Check if user is supervisor
        if (Auth::guard('supervisor')->check()) {
            $supervisor = Auth::guard('supervisor')->user();
            $modulePermission = $supervisor->permissions()->where('module_id', $moduleId)->first();
            if ($modulePermission) {
                return $modulePermission->$permission ?? false;
            }
        } else {
            // Check if user is regular user
            $user = Auth::user();
            if ($user) {
                // Admin and SuperAdmin always have full access
                if ($user->isAdmin() || $user->isSuperAdmin()) {
                    return true;
                }
                
                $modulePermission = $user->modules()->where('module_id', $moduleId)->first();
                if ($modulePermission) {
                    return $modulePermission->pivot->$permission ?? false;
                }
            }
        }
        return false;
    }
}

if (!function_exists('canEditUsers')) {
    function canEditUsers()
    {
        return hasPermission(1, 'can_edit_users'); // HRM module
    }
}

if (!function_exists('canDeleteUsers')) {
    function canDeleteUsers()
    {
        return hasPermission(1, 'can_delete_users'); // HRM module
    }
}

if (!function_exists('canMarkSalaryPaid')) {
    function canMarkSalaryPaid()
    {
        return hasPermission(2, 'can_mark_salary_paid'); // Finance module
    }
}

if (!function_exists('canViewSalaryData')) {
    function canViewSalaryData()
    {
        return hasPermission(2, 'can_view_salary_data'); // Finance module
    }
}
