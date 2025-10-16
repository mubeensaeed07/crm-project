<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Module;
use App\Models\UserModule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        
        // Redirect SuperAdmins and Admins to their appropriate dashboards
        if ($user->isSuperAdmin()) {
            return redirect()->route('superadmin.dashboard');
        } elseif ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        
        $userModules = UserModule::where('user_id', $user->id)
            ->with('module')
            ->get();
        
        return view('user.dashboard', compact('userModules'));
    }

    public function showModule($moduleId)
    {
        $user = Auth::user();
        $userModule = UserModule::where('user_id', $user->id)
            ->where('module_id', $moduleId)
            ->with('module')
            ->first();
        
        if (!$userModule) {
            return redirect()->route('user.dashboard')->with('error', 'You do not have access to this module.');
        }
        
        // Get module permissions
        $permissions = [
            'can_create_users' => $userModule->pivot->can_create_users ?? false,
            'can_edit_users' => $userModule->pivot->can_edit_users ?? false,
            'can_delete_users' => $userModule->pivot->can_delete_users ?? false,
            'can_reset_passwords' => $userModule->pivot->can_reset_passwords ?? false,
            'can_assign_modules' => $userModule->pivot->can_assign_modules ?? false,
            'can_view_reports' => $userModule->pivot->can_view_reports ?? false,
            'can_mark_salary_paid' => $userModule->pivot->can_mark_salary_paid ?? false,
            'can_mark_salary_pending' => $userModule->pivot->can_mark_salary_pending ?? false,
            'can_view_salary_data' => $userModule->pivot->can_view_salary_data ?? false,
            'can_manage_salary_payments' => $userModule->pivot->can_manage_salary_payments ?? false,
        ];
        
        $module = $userModule->module;
        
        return view('user.module', compact('module', 'permissions', 'user'));
    }

    public function getMyModules()
    {
        $user = Auth::user();
        $userModules = UserModule::where('user_id', $user->id)
            ->with('module')
            ->get();
        
        return response()->json($userModules);
    }

    public function profile()
    {
        $user = Auth::user();
        $user->load('userInfo');
        
        // Get departments for the user's admin
        $departments = collect();
        if ($user->userInfo && $user->userInfo->admin_id) {
            $departments = \App\Models\Department::where('admin_id', $user->userInfo->admin_id)
                ->where('is_active', true)
                ->orderBy('name')
                ->get();
        }
        
        return view('user.profile', compact('user', 'departments'));
    }

    /**
     * Calculate profile completion percentage
     */
    public static function calculateProfileCompletion($user)
    {
        $user->load('userInfo');
        
        // Define profile fields and their locations
        $profileFields = [
            'first_name' => 'user', // Direct on user table
            'last_name' => 'user',   // Direct on user table
            'email' => 'user',       // Direct on user table
            'phone' => 'userInfo',   // In user_info table
            'date_of_birth' => 'userInfo',
            'gender' => 'userInfo',
            'address' => 'userInfo',
            'city' => 'userInfo',
            'state' => 'userInfo',
            'country' => 'userInfo',
            'postal_code' => 'userInfo',
            'job_title' => 'userInfo',
            'department_id' => 'userInfo',
            'company' => 'userInfo',
            'bio' => 'user',
            'avatar' => 'userInfo'
        ];
        
        $completedFields = 0;
        $totalFields = count($profileFields);
        
        foreach($profileFields as $field => $location) {
            $value = null;
            if($location === 'user') {
                $value = $user->$field;
            } elseif($location === 'userInfo' && $user->userInfo) {
                $value = $user->userInfo->$field;
            }
            
            if(!empty($value)) {
                $completedFields++;
            }
        }
        
        return round(($completedFields / $totalFields) * 100);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $validator = \Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'gmail' => 'nullable|email|max:255',
            'cnic' => 'nullable|string|max:20',
            'passport' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female,other',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'job_title' => 'nullable|string|max:100',
            'department_id' => 'nullable|exists:departments,id',
            'company' => 'nullable|string|max:100',
            'bio' => 'nullable|string|max:1000',
            'linkedin_url' => 'nullable|url|max:255',
            'twitter_url' => 'nullable|url|max:255',
            'website_url' => 'nullable|url|max:255',
            'emergency_contact_name' => 'nullable|string|max:100',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'emergency_contact_relationship' => 'nullable|string|max:50',
            'timezone' => 'nullable|string|max:50',
            'language' => 'nullable|string|max:10',
            'email_notifications' => 'boolean',
            'sms_notifications' => 'boolean',
            // Password change validation
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:8|confirmed',
            'new_password_confirmation' => 'nullable|required_with:new_password',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Handle password change
        if ($request->filled('new_password')) {
            // Verify current password
            if (!Hash::check($request->current_password, $user->password)) {
                return redirect()->back()
                    ->withErrors(['current_password' => 'Current password is incorrect'])
                    ->withInput();
            }
            
            // Update password
            $user->update([
                'password' => Hash::make($request->new_password)
            ]);
        }

        $data = $request->except(['avatar', 'email_notifications', 'sms_notifications', 'current_password', 'new_password', 'new_password_confirmation']);
        
        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $avatarName = time() . '_' . $user->id . '.' . $avatar->getClientOriginalExtension();
            $avatar->storeAs('public/avatars', $avatarName);
            $data['avatar'] = 'avatars/' . $avatarName;
        }
        
        // Handle boolean fields
        $data['email_notifications'] = $request->has('email_notifications');
        $data['sms_notifications'] = $request->has('sms_notifications');
        
        // Update user basic info
        $user->update([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email']
        ]);

        // Update or create user info
        $userInfo = $user->userInfo;
        if (!$userInfo) {
            $userInfo = $user->userInfo()->create($data);
        } else {
            $userInfo->update($data);
        }

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }
}
