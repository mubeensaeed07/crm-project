<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Module;
use App\Models\UserModule;
use App\Models\UserType;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Mail\UserRegisteredMail;
use App\Mail\PasswordResetMail;

class AdminController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $user->load('userInfo.userType');
        return view('admin.dashboard', compact('user'));
    }

    public function users()
    {
        // Only show users that belong to the current admin
        $users = User::where('role_id', 3)
                    ->where('admin_id', auth()->id())
                    ->with(['userInfo.userType', 'userModules.module'])
                    ->get();
        $modules = Module::all();
        // Only show "User" type for user creation
        $userTypes = UserType::where('name', 'User')->get();
        
        // Get departments for the current admin
        $departments = \App\Models\Department::where('admin_id', auth()->id())
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
        
        $userModules = UserModule::with(['user', 'module'])
                    ->whereHas('user', function($query) {
                        $query->where('admin_id', auth()->id());
                    })
                    ->get();
        
        return view('admin.users', compact('users', 'modules', 'userTypes', 'userModules', 'departments'));
    }

    public function modules()
    {
        $modules = Module::all();
        return view('admin.modules', compact('modules'));
    }

    public function settings()
    {
        return view('admin.settings');
    }

    public function addUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'user_type' => 'required|exists:user_types,id',
            'salary' => 'nullable|numeric|min:0',
            'phone' => 'nullable|string|max:20',
            'gmail' => 'nullable|email|max:255',
            'cnic' => 'nullable|string|max:20',
            'passport' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'job_title' => 'nullable|string|max:100',
            'department_id' => 'nullable|exists:departments,id',
            'company' => 'nullable|string|max:100',
            'modules' => 'required|array|min:1',
            'modules.*' => 'exists:modules,id'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Generate a random password
        $password = $this->generateRandomPassword();
        
        // Get the SuperAdmin ID - try multiple methods
        $currentAdmin = auth()->user();
        $superAdminId = null;
        
        // Method 1: Get from current admin's user_info
        if ($currentAdmin->userInfo && $currentAdmin->userInfo->superadmin_id) {
            $superAdminId = $currentAdmin->userInfo->superadmin_id;
        }
        // Method 2: If current admin is SuperAdmin (role_id = 1), use their own ID
        elseif ($currentAdmin->role_id == 1) {
            $superAdminId = $currentAdmin->id;
        }
        // Method 3: Find any SuperAdmin in the system
        else {
            $superAdmin = User::where('role_id', 1)->first();
            $superAdminId = $superAdmin ? $superAdmin->id : null;
        }
        
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            // name field removed - using first_name and last_name separately
            'email' => $request->email,
            'role_id' => 3, // User role
            'admin_id' => auth()->id(), // Assign to current admin
            'superadmin_id' => $superAdminId, // Set superadmin_id in users table
            'is_approved' => true, // Admin-created users are automatically approved
            'password' => Hash::make($password),
            'salary' => $request->salary, // Add salary field
            'phone' => $request->phone, // Add phone field
            'created_by_type' => 'admin', // Admin is creating this user
            'created_by_id' => auth()->id() // Current admin's ID
        ]);
        
        // Create user info with hierarchy and user type
        $user->userInfo()->create([
            'admin_id' => auth()->id(), // Current admin is their admin
            'superadmin_id' => $superAdminId, // Same superadmin as the current admin
            'user_type_id' => $request->user_type, // User type assigned by admin
            'phone' => $request->phone,
            'gmail' => $request->gmail,
            'cnic' => $request->cnic,
            'passport' => $request->passport,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'country' => $request->country,
            'postal_code' => $request->postal_code,
            'job_title' => $request->job_title,
            'department_id' => $request->department_id,
            'company' => $request->company
        ]);
        
        // Create user identification record
        DB::table('user_identification')->insert([
            'user_id' => $user->id,
            'admin_id' => auth()->id(),
            'superadmin_id' => $superAdminId,
            'user_role' => 'user',
            'status' => 'active',
            'approved_at' => now(),
            'assigned_at' => now(),
            'notes' => 'Created by admin: ' . auth()->user()->full_name,
            'created_at' => now(),
            'updated_at' => now()
        ]);


        // Assign modules to user
        foreach ($request->modules as $moduleId) {
            UserModule::create([
                'user_id' => $user->id,
                'module_id' => $moduleId
            ]);
        }

        // Send email notification to the new user
        try {
            Mail::to($user->email)->send(new UserRegisteredMail($user, $password, auth()->user()->full_name));
        } catch (\Exception $e) {
            // Log the error but don't fail the user creation
            \Log::error('Failed to send user registration email: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'User added successfully! An email with login credentials has been sent to the user.');
    }

    public function showEditUser($id)
    {
        // Only allow editing users that belong to the current admin
        $user = User::where('id', $id)
                   ->where('admin_id', auth()->id())
                   ->firstOrFail();
        
        $modules = Module::all();
        // Only show "User" type for user creation
        $userTypes = UserType::where('name', 'User')->get();
        
        // Get current user type from user_info
        $currentUserType = $user->userInfo ? $user->userInfo->user_type_id : null;
        
        // Get current user modules
        $userModuleIds = $user->userModules->pluck('module_id')->toArray();
        
        return view('admin.edit-user', compact('user', 'modules', 'userTypes', 'currentUserType', 'userModuleIds'));
    }

    public function editUser(Request $request, $id)
    {
        // Only allow editing users that belong to the current admin
        $user = User::where('id', $id)
                   ->where('admin_id', auth()->id())
                   ->firstOrFail();
        
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'user_type' => 'required|exists:user_types,id',
            'modules' => 'required|array|min:1',
            'modules.*' => 'exists:modules,id'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
        ]);

        // Update user type in user_info
        if ($user->userInfo) {
            $user->userInfo->update(['user_type_id' => $request->user_type]);
        } else {
            // Create user_info if it doesn't exist
            $user->userInfo()->create(['user_type_id' => $request->user_type]);
        }

        // Update user modules
        UserModule::where('user_id', $user->id)->delete();
        foreach ($request->modules as $moduleId) {
            UserModule::create([
                'user_id' => $user->id,
                'module_id' => $moduleId
            ]);
        }

        return redirect()->route('admin.dashboard')->with('success', 'User updated successfully!');
    }

    public function deleteUser($id)
    {
        // Only allow deleting users that belong to the current admin
        $user = User::where('id', $id)
                   ->where('admin_id', auth()->id())
                   ->firstOrFail();
        
        UserModule::where('user_id', $user->id)->delete();
        $user->delete();

        return redirect()->back()->with('success', 'User deleted successfully!');
    }

    public function getUsers()
    {
        // Only return users that belong to the current admin
        $users = User::where('role_id', 3)
                    ->where('admin_id', auth()->id())
                    ->with('userModules.module')
                    ->get();
        return response()->json($users);
    }

    public function resetUserPassword($id)
    {
        // Only allow resetting password for users that belong to the current admin
        $user = User::where('id', $id)
                   ->where('admin_id', auth()->id())
                   ->firstOrFail();
        
        // Generate a new random password
        $newPassword = $this->generateRandomPassword();
        
        // Update user password
        $user->update([
            'password' => Hash::make($newPassword)
        ]);

        // Send email notification to the user
        try {
            Mail::to($user->email)->send(new PasswordResetMail($user, $newPassword));
        } catch (\Exception $e) {
            \Log::error('Failed to send password reset email: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Password reset but failed to send email notification.');
        }

        return redirect()->back()->with('success', 'Password reset successfully! An email with new credentials has been sent to the user.');
    }

    private function generateRandomPassword($length = 12)
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*';
        $password = '';
        
        for ($i = 0; $i < $length; $i++) {
            $password .= $characters[rand(0, strlen($characters) - 1)];
        }
        
        return $password;
    }
}
