<?php

namespace Modules\SUPPORT\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\Module;
use App\Models\Department;
use App\Models\UserType;

class SUPPORTController extends Controller
{
    /**
     * Display the SUPPORT dashboard.
     * NO PERMISSION CHECKS - Accessible to all users
     */
    public function dashboard()
    {
        // Get current user info
        $currentUser = Auth::user();
        $isSupervisor = Auth::guard('supervisor')->check();
        
        // Get basic statistics for the dashboard
        $totalUsers = 0;
        $activeUsers = 0;
        
        if ($currentUser) {
            // Get users under the same admin
            $users = \App\Models\User::where('admin_id', $currentUser->admin_id)->get();
            $totalUsers = $users->count();
            $activeUsers = $users->where('is_approved', true)->count();
        }
        
        return view('support::dashboard', compact('totalUsers', 'activeUsers'));
    }

    /**
     * Display User Support option.
     * NO PERMISSION CHECKS - Accessible to all users
     */
    public function userSupport()
    {
        return view('support::user.index');
    }

    /**
     * Display Dealer Support option.
     * NO PERMISSION CHECKS - Accessible to all users
     */
    public function dealerSupport()
    {
        return view('support::dealer.index');
    }

    /**
     * Search for users via API.
     * NO PERMISSION CHECKS - Accessible to all users
     */
    public function searchUsers(Request $request)
    {
        $searchTerm = $request->input('search');
        
        // TODO: Replace with actual API call
        // For now, return mock data
        $users = [
            [
                'id' => 1,
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'phone' => '+1234567890',
                'status' => 'Active'
            ],
            [
                'id' => 2,
                'name' => 'Jane Smith',
                'email' => 'jane@example.com',
                'phone' => '+1234567891',
                'status' => 'Active'
            ]
        ];
        
        return response()->json($users);
    }

    /**
     * Search for dealers via API.
     * NO PERMISSION CHECKS - Accessible to all users
     */
    public function searchDealers(Request $request)
    {
        $searchTerm = $request->input('search');
        
        // TODO: Replace with actual API call
        // For now, return mock data
        $dealers = [
            [
                'id' => 1,
                'name' => 'ABC Motors',
                'email' => 'contact@abcmotors.com',
                'phone' => '+1234567890',
                'location' => 'New York',
                'status' => 'Active'
            ],
            [
                'id' => 2,
                'name' => 'XYZ Auto',
                'email' => 'info@xyzauto.com',
                'phone' => '+1234567891',
                'location' => 'California',
                'status' => 'Active'
            ]
        ];
        
        return response()->json($dealers);
    }

    /**
     * Show user profile.
     * NO PERMISSION CHECKS - Accessible to all users
     */
    public function showUser($id)
    {
        // TODO: Fetch user data from API based on ID
        $user = [
            'id' => $id,
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '+1234567890',
            'status' => 'Active',
            'registration_date' => '2023-01-15',
            'last_login' => '2025-10-02',
            'support_tickets' => 5,
            'resolved_tickets' => 4
        ];
        
        return view('support::user.profile', compact('user'));
    }

    /**
     * Show dealer profile.
     * NO PERMISSION CHECKS - Accessible to all users
     */
    public function showDealer($id)
    {
        // TODO: Fetch dealer data from API based on ID
        $dealer = [
            'id' => $id,
            'name' => 'ABC Motors',
            'email' => 'contact@abcmotors.com',
            'phone' => '+1234567890',
            'location' => 'New York',
            'status' => 'Active',
            'registration_date' => '2023-01-15',
            'last_login' => '2025-10-02',
            'support_tickets' => 12,
            'resolved_tickets' => 10
        ];
        
        return view('support::dealer.profile', compact('dealer'));
    }

    /**
     * Display the create user form.
     * NO PERMISSION CHECKS - Accessible to all users
     */
    public function createUser()
    {
        // Get only SUPPORT module (pre-selected)
        $modules = Module::where('name', 'SUPPORT')->get();
        
        // Get departments for selection
        $currentUser = Auth::user();
        
        if ($currentUser && $currentUser->isAdmin()) {
            // Admin sees their own departments
            $departments = Department::where('admin_id', $currentUser->id)
                ->where('is_active', true)
                ->orderBy('name')
                ->get();
        } elseif ($currentUser && $currentUser->admin_id) {
            // Supervisor sees departments from their assigned admin
            $departments = Department::where('admin_id', $currentUser->admin_id)
                ->where('is_active', true)
                ->orderBy('name')
                ->get();
        } else {
            $departments = collect(); // Empty collection if no admin assigned
        }
        
        // Get user types for selection (only User type)
        $userTypes = UserType::where('name', 'User')->get();
        
        return view('support::users.create', compact('modules', 'departments', 'userTypes'));
    }

    /**
     * Store a newly created user.
     * NO PERMISSION CHECKS - Accessible to all users
     */
    public function storeUser(Request $request)
    {
        // Validate the request
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
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
            'salary' => 'nullable|numeric|min:0',
            'user_type_id' => 'required|exists:user_types,id',
            'modules' => 'required|array|min:1',
            'modules.*' => 'exists:modules,id',
        ]);

        // Get current user
        $currentUser = Auth::user();
        
        // Determine superadmin_id
        $superAdminId = null;
        if ($currentUser->userInfo && $currentUser->userInfo->superadmin_id) {
            $superAdminId = $currentUser->userInfo->superadmin_id;
        } else {
            // Find any SuperAdmin
            $superAdmin = User::whereHas('userInfo', function($query) {
                $query->where('user_type_id', 1); // SuperAdmin type
            })->first();
            if ($superAdmin) {
                $superAdminId = $superAdmin->id;
            }
        }

        // Generate a secure random password
        $generatedPassword = $this->generateSecurePassword();
        
        // Create the user
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($generatedPassword),
            'role_id' => 3, // User role
            'is_approved' => true,
            'admin_id' => $currentUser->id,
            'superadmin_id' => $superAdminId,
        ]);

        // Create user info
        $user->userInfo()->create([
            'admin_id' => $currentUser->id,
            'superadmin_id' => $superAdminId,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
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
            'company' => $request->company,
            'salary' => $request->salary,
            'user_type_id' => $request->user_type_id,
            'created_by_type' => 'admin',
            'created_by_id' => $currentUser->id
        ]);

        // Assign modules to the user
        if ($request->has('modules') && is_array($request->modules)) {
            foreach ($request->modules as $moduleId) {
                $module = Module::find($moduleId);
                if ($module) {
                    // For SUPPORT module, give full permissions automatically
                    if ($module->name === 'SUPPORT') {
                        $user->modules()->attach($moduleId, [
                            'can_view_reports' => true,
                            'can_create_users' => true,
                            'can_edit_users' => true,
                            'can_delete_users' => true,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                    } else {
                        // For other modules, use default permissions
                        $user->modules()->attach($moduleId, [
                            'can_view_reports' => true,
                            'can_create_users' => false,
                            'can_edit_users' => false,
                            'can_delete_users' => false,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                    }
                }
            }
        }

        // Send welcome email to the new user
        try {
            $adminName = $currentUser->full_name;
            
            Mail::to($user->email)->send(new \App\Mail\UserRegisteredMail(
                $user,
                $generatedPassword, // Use generated secure password
                $adminName
            ));
        } catch (\Exception $e) {
            // Log error but don't fail the user creation
            \Log::error('Failed to send welcome email', [
                'user_id' => $user->id,
                'email' => $user->email,
                'error' => $e->getMessage()
            ]);
        }

        return redirect()->route('support.users.index')->with('success', 'User created successfully! Welcome email sent.');
    }

    /**
     * Display a listing of users.
     * NO PERMISSION CHECKS - Accessible to all users
     */
    public function users()
    {
        // Get current user
        $currentUser = Auth::user();
        
        // Get users for current admin
        $users = User::where('admin_id', $currentUser->id)->with('userInfo')->get();
        
        // Calculate statistics
        $totalUsers = $users->count();
        $activeUsers = $users->where('is_approved', true)->count();
        $inactiveUsers = $users->where('is_approved', false)->count();
        
        return view('support::users.index', compact('users', 'totalUsers', 'activeUsers', 'inactiveUsers'));
    }

    /**
     * Generate a secure random password
     */
    public function generateSecurePassword($length = 12)
    {
        $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $lowercase = 'abcdefghijklmnopqrstuvwxyz';
        $numbers = '0123456789';
        $symbols = '!@#$%^&*()_+-=[]{}|;:,.<>?';
        
        $allChars = $uppercase . $lowercase . $numbers . $symbols;
        
        $password = '';
        
        // Ensure at least one character from each category
        $password .= $uppercase[random_int(0, strlen($uppercase) - 1)];
        $password .= $lowercase[random_int(0, strlen($lowercase) - 1)];
        $password .= $numbers[random_int(0, strlen($numbers) - 1)];
        $password .= $symbols[random_int(0, strlen($symbols) - 1)];
        
        // Fill the rest with random characters
        for ($i = 4; $i < $length; $i++) {
            $password .= $allChars[random_int(0, strlen($allChars) - 1)];
        }
        
        // Shuffle the password to randomize positions
        return str_shuffle($password);
    }
}
