<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SecurityHelper
{
    /**
     * Get current user with security logging
     */
    public static function getCurrentUserWithLogging()
    {
        $user = Auth::user();
        $supervisor = Auth::guard('supervisor')->user();
        
        if ($user) {
            Log::info('User access', [
                'user_id' => $user->id,
                'user_name' => $user->full_name,
                'role_id' => $user->role_id,
                'admin_id' => $user->admin_id,
                'is_admin' => $user->isAdmin(),
                'is_superadmin' => $user->isSuperAdmin(),
                'is_user' => $user->isUser(),
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
        }
        
        if ($supervisor) {
            Log::info('Supervisor access', [
                'supervisor_id' => $supervisor->id,
                'supervisor_name' => $supervisor->full_name,
                'admin_id' => $supervisor->admin_id,
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
        }
        
        return [
            'user' => $user,
            'supervisor' => $supervisor,
            'isSupervisor' => (bool) $supervisor
        ];
    }
    
    /**
     * Validate admin data access
     */
    public static function validateAdminDataAccess($adminId, $resourceAdminId)
    {
        if ($adminId != $resourceAdminId) {
            Log::warning('SECURITY BREACH ATTEMPT: Admin trying to access other admin data', [
                'current_admin_id' => $adminId,
                'resource_admin_id' => $resourceAdminId,
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            return false;
        }
        return true;
    }
    
    /**
     * Get secure user query for current admin
     */
    public static function getSecureUserQuery($adminId = null)
    {
        $user = Auth::user();
        $supervisor = Auth::guard('supervisor')->user();
        
        if (!$adminId) {
            if ($user) {
                if ($user->isAdmin() || $user->isSuperAdmin()) {
                    $adminId = $user->id;
                } else {
                    $adminId = $user->admin_id;
                }
            } elseif ($supervisor) {
                $adminId = $supervisor->admin_id;
            }
        }
        
        if (!$adminId) {
            Log::error('SECURITY ERROR: No admin ID found for user query');
            return \App\Models\User::where('id', -1); // Return empty query
        }
        
        return \App\Models\User::where('admin_id', $adminId);
    }
    
    /**
     * Log security events
     */
    public static function logSecurityEvent($event, $data = [])
    {
        Log::info('Security Event: ' . $event, array_merge($data, [
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'timestamp' => now()
        ]));
    }
    
    /**
     * Check if user can access resource
     */
    public static function canAccessResource($resourceAdminId)
    {
        $user = Auth::user();
        $supervisor = Auth::guard('supervisor')->user();
        
        if ($user) {
            if ($user->isAdmin() || $user->isSuperAdmin()) {
                return $user->id == $resourceAdminId;
            } else {
                return $user->admin_id == $resourceAdminId;
            }
        } elseif ($supervisor) {
            return $supervisor->admin_id == $resourceAdminId;
        }
        
        return false;
    }
}
