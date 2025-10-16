<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supervisor;
use App\Models\Module;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SupervisorAuthController extends Controller
{
    public function dashboard()
    {
        $supervisor = Auth::guard('supervisor')->user();
        $modules = $supervisor->modules;
        
        // Get statistics
        $totalModules = $modules->count();
        $activeModules = $modules->where('is_active', true)->count();
        
        // Supervisor dashboard only shows module access, not HRM-specific data
        return view('supervisor.dashboard', compact('supervisor', 'modules', 'totalModules', 'activeModules'));
    }

    public function profile()
    {
        $supervisor = Auth::guard('supervisor')->user();
        return view('supervisor.profile', compact('supervisor'));
    }

    public function module($moduleId)
    {
        $supervisor = Auth::guard('supervisor')->user();
        $module = Module::findOrFail($moduleId);
        
        // Check if supervisor has access to this module
        $supervisorModule = $supervisor->modules()->where('module_id', $moduleId)->first();
        
        if (!$supervisorModule) {
            return redirect()->route('supervisor.dashboard')->with('error', 'You do not have access to this module.');
        }
        
        // Get module permissions
        $permissions = [
            'can_create_users' => $supervisorModule->pivot->can_create_users,
            'can_edit_users' => $supervisorModule->pivot->can_edit_users,
            'can_delete_users' => $supervisorModule->pivot->can_delete_users,
            'can_reset_passwords' => $supervisorModule->pivot->can_reset_passwords,
            'can_assign_modules' => $supervisorModule->pivot->can_assign_modules,
            'can_view_reports' => $supervisorModule->pivot->can_view_reports,
        ];
        
        // Route to appropriate module controller based on module name
        switch (strtolower($module->name)) {
            case 'hrm':
            case 'human resource management':
                return redirect()->route('hrm.dashboard');
            case 'crm':
            case 'customer relationship management':
                return redirect()->route('crm.dashboard');
            case 'inventory':
                return redirect()->route('inventory.dashboard');
            case 'finance':
                return redirect()->route('finance.dashboard');
            case 'support':
                return redirect()->route('support.dashboard');
            default:
                // Generic module view
                return view('supervisor.module', compact('module', 'permissions', 'supervisor'));
        }
    }

    public function updateProfile(Request $request)
    {
        $supervisor = Auth::guard('supervisor')->user();
        
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:supervisors,email,' . $supervisor->id,
            // Password change validation
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:8|confirmed',
            'new_password_confirmation' => 'nullable|required_with:new_password',
        ]);

        // Handle password change
        if ($request->filled('new_password')) {
            // Verify current password
            if (!Hash::check($request->current_password, $supervisor->password)) {
                return redirect()->back()
                    ->withErrors(['current_password' => 'Current password is incorrect'])
                    ->withInput();
            }
            
            // Update password
            $supervisor->update([
                'password' => Hash::make($request->new_password)
            ]);
        }

        $supervisor->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
        ]);

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }

    public function logout()
    {
        Auth::guard('supervisor')->logout();
        session()->forget(['user_type', 'supervisor_id']);
        
        return redirect()->route('login')->with('success', 'Logged out successfully!');
    }
}
