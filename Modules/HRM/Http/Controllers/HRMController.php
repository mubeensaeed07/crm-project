<?php

namespace Modules\HRM\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class HRMController extends Controller
{
    /**
     * Get current authenticated user (admin or supervisor)
     */
    private function getCurrentUser()
    {
        // Check supervisor guard first
        $supervisorUser = Auth::guard('supervisor')->user();
        if ($supervisorUser) {
            \Log::info('Supervisor detected in getCurrentUser', [
                'supervisor_id' => $supervisorUser->id,
                'email' => $supervisorUser->email,
                'status' => $supervisorUser->status
            ]);
            return ['user' => $supervisorUser, 'isSupervisor' => true];
        }
        
        // If no supervisor, check web guard
        $webUser = Auth::user();
        if ($webUser) {
            \Log::info('Web user detected in getCurrentUser', [
                'user_id' => $webUser->id,
                'email' => $webUser->email,
                'role_id' => $webUser->role_id
            ]);
            return ['user' => $webUser, 'isSupervisor' => false];
        }
        
        // No user authenticated
        \Log::warning('No user authenticated in getCurrentUser', [
            'supervisor_check' => Auth::guard('supervisor')->check(),
            'web_check' => Auth::check()
        ]);
        return ['user' => null, 'isSupervisor' => false];
    }
    
    /**
     * Get users based on current user's role and data isolation
     */
    private function getUsersForCurrentUser($withRelations = [])
    {
        try {
            $auth = $this->getCurrentUser();
            $currentUser = $auth['user'];
            $isSupervisor = $auth['isSupervisor'];
            
            // Debug logging
            \Log::info('getUsersForCurrentUser debug', [
                'currentUser' => $currentUser ? $currentUser->id : null,
                'isSupervisor' => $isSupervisor,
                'userClass' => $currentUser ? get_class($currentUser) : null,
                'isAdmin' => $currentUser ? $currentUser->isAdmin() : false,
                'isSuperAdmin' => $currentUser ? $currentUser->isSuperAdmin() : false,
                'isSupervisorMethod' => $currentUser ? $currentUser->isSupervisor() : false
            ]);
            
            // Check if we have a valid user first
            if (!$currentUser) {
                \Log::warning('No current user found, returning empty collection');
                return collect();
            }
            
            // Build query based on user type - use separate methods to avoid flow issues
            if ($currentUser->isAdmin() || $currentUser->isSuperAdmin()) {
                return $this->getAdminUsers($currentUser, $withRelations);
            } elseif ($currentUser->isSupervisor() || $isSupervisor) {
                return $this->getSupervisorUsers($currentUser, $withRelations);
            } elseif ($currentUser->isUser()) {
                return $this->getUserUsers($currentUser, $withRelations);
            } else {
                \Log::warning('No valid user type found, returning empty collection');
                return collect();
            }
            
        } catch (\Exception $e) {
            \Log::error('Error in getUsersForCurrentUser: ' . $e->getMessage());
            return collect();
        }
    }
    
    private function getAdminUsers($currentUser, $withRelations = [])
    {
        try {
            \Log::info('Using admin query for user: ' . $currentUser->id);
            
            // Use a direct query approach to avoid any issues
            $adminId = $currentUser->id;
            
            if (empty($adminId)) {
                \Log::error('Admin ID is empty');
                return collect();
            }
            
            // Build the query step by step
            $query = User::query();
            $query->where('admin_id', $adminId);
            
            // Add relationships if provided
            if (!empty($withRelations)) {
                $query->with($withRelations);
            }
            
            // Execute the query
            $results = $query->get();
            \Log::info('Admin query executed successfully, returned ' . $results->count() . ' users');
            
            return $results;
            
        } catch (\Exception $e) {
            \Log::error('Error in getAdminUsers: ' . $e->getMessage());
            return collect();
        }
    }
    
    private function getSupervisorUsers($currentUser, $withRelations = [])
    {
        try {
            \Log::info('Using supervisor query for admin_id: ' . $currentUser->admin_id);
            
            // Use a direct query approach to avoid any issues
            $adminId = $currentUser->admin_id;
            
            if (empty($adminId)) {
                \Log::error('Admin ID is empty for supervisor');
                return collect();
            }
            
            // Build the query step by step
            $query = User::query();
            $query->where('admin_id', $adminId);
            
            // Add relationships if provided
            if (!empty($withRelations)) {
                $query->with($withRelations);
            }
            
            // Execute the query
            $results = $query->get();
            \Log::info('Supervisor query executed successfully, returned ' . $results->count() . ' users');
            
            return $results;
            
        } catch (\Exception $e) {
            \Log::error('Error in getSupervisorUsers: ' . $e->getMessage());
            return collect();
        }
    }
    
    private function getUserUsers($currentUser, $withRelations = [])
    {
        try {
            \Log::info('Using user query for admin_id: ' . $currentUser->admin_id);
            
            if (empty($currentUser->admin_id)) {
                \Log::error('User admin_id is empty');
                return collect();
            }
            
            // Regular users see other users under the same admin
            $query = User::query();
            $query->where('admin_id', $currentUser->admin_id);
            
            if (!empty($withRelations)) {
                $query->with($withRelations);
            }
            
            $results = $query->get();
            \Log::info('User query executed successfully, returned ' . $results->count() . ' users');
            
            return $results;
            
        } catch (\Exception $e) {
            \Log::error('Error in getUserUsers: ' . $e->getMessage());
            return collect();
        }
    }
    
    /**
     * Check if current user has permission for HRM module
     */
    private function checkHRMPermission($permission)
    {
        $auth = $this->getCurrentUser();
        $currentUser = $auth['user'];
        $isSupervisor = $auth['isSupervisor'];
        
        \Log::info('HRM Permission Check', [
            'permission' => $permission,
            'user_id' => $currentUser ? $currentUser->id : null,
            'is_supervisor' => $isSupervisor,
            'user_class' => $currentUser ? get_class($currentUser) : null
        ]);
        
        // Admin and SuperAdmin always have access
        if ($currentUser && ($currentUser->isAdmin() || $currentUser->isSuperAdmin())) {
            \Log::info('Admin/SuperAdmin access granted for HRM');
            return true;
        }
        
        // Check supervisor permissions for HRM module (ID: 1)
        if ($isSupervisor && $currentUser) {
            // Log supervisor's modules and permissions
            $supervisorModules = $currentUser->modules()->get();
            \Log::info('Supervisor modules', [
                'modules' => $supervisorModules->pluck('name', 'id')->toArray(),
                'hrm_module_exists' => $supervisorModules->where('id', 1)->isNotEmpty()
            ]);
            
            // Check if supervisor has HRM module (ID: 1)
            $hasHRMModule = $supervisorModules->where('id', 1)->isNotEmpty();
            if (!$hasHRMModule) {
                \Log::warning('Supervisor does not have HRM module', [
                    'supervisor_id' => $currentUser->id,
                    'available_modules' => $supervisorModules->pluck('name', 'id')->toArray()
                ]);
                return false;
            }
            
            $hasPermission = $currentUser->hasPermission(1, $permission);
            \Log::info('Supervisor HRM permission check', [
                'has_permission' => $hasPermission,
                'module_id' => 1,
                'permission' => $permission,
                'supervisor_id' => $currentUser->id
            ]);
            return $hasPermission;
        }
        
        // Check user permissions for HRM module (ID: 1)
        if ($currentUser && $currentUser->isUser()) {
            $hasPermission = $currentUser->hasPermission(1, $permission);
            \Log::info('User HRM permission check', [
                'has_permission' => $hasPermission,
                'module_id' => 1,
                'permission' => $permission,
                'user_id' => $currentUser->id
            ]);
            return $hasPermission;
        }
        
        \Log::warning('No permission granted for HRM', [
            'user_id' => $currentUser ? $currentUser->id : null,
            'is_supervisor' => $isSupervisor
        ]);
        return false;
    }

    public function dashboard()
    {
        // Debug: Log permission check
        $auth = $this->getCurrentUser();
        $currentUser = $auth['user'];
        $isSupervisor = $auth['isSupervisor'];
        
        \Log::info('HRM Dashboard Access Attempt', [
            'user_id' => $currentUser ? $currentUser->id : null,
            'is_supervisor' => $isSupervisor,
            'user_type' => $currentUser ? get_class($currentUser) : null
        ]);
        
        // Check supervisor permissions for HRM module
        if (!$this->checkHRMPermission('can_view_reports')) {
            \Log::warning('HRM Access Denied', [
                'user_id' => $currentUser ? $currentUser->id : null,
                'is_supervisor' => $isSupervisor
            ]);
            return redirect()->back()->with('error', 'You do not have permission to access HRM dashboard.');
        }
        
        $users = $this->getUsersForCurrentUser(['userInfo']);
        
        // Calculate dynamic statistics
        $totalEmployees = $users->count();
        $activeEmployees = $users->where('is_approved', true)->count();
        $totalSalary = $users->sum(function($user) {
            return $user->userInfo ? $user->userInfo->salary : 0;
        });
        
        // Get total active departments count
        if ($currentUser && $currentUser->isAdmin()) {
            // Admin sees their own departments
            $departments = \App\Models\Department::where('admin_id', $currentUser->id)
                ->where('is_active', true)
                ->count();
        } elseif ($isSupervisor && $currentUser && $currentUser->admin_id) {
            // Supervisor sees departments from their assigned admin
            $departments = \App\Models\Department::where('admin_id', $currentUser->admin_id)
                ->where('is_active', true)
                ->count();
        } else {
            $departments = 0;
        }
        
        // Set permission variables for the view
        $canCreateUsers = $this->checkHRMPermission('can_create_users');
        $canEditUsers = $this->checkHRMPermission('can_edit_users');
        $canDeleteUsers = $this->checkHRMPermission('can_delete_users');
        $canViewReports = $this->checkHRMPermission('can_view_reports');
        
        return view('hrm::dashboard', compact(
            'users',
            'totalSalary', 
            'totalEmployees', 
            'activeEmployees', 
            'departments',
            'canCreateUsers',
            'canEditUsers', 
            'canDeleteUsers',
            'canViewReports'
        ));
    }

    public function employees()
    {
        // Check supervisor permissions for HRM module
        if (!$this->checkHRMPermission('can_view_reports')) {
            return redirect()->back()->with('error', 'You do not have permission to view employees.');
        }
        
        $users = $this->getUsersForCurrentUser(['userInfo', 'userInfo.department']);
        
        return view('hrm::employees.index', compact('users'));
    }

    public function departments()
    {
        // Check supervisor permissions for HRM module
        if (!$this->checkHRMPermission('can_view_reports')) {
            return redirect()->back()->with('error', 'You do not have permission to view departments.');
        }
        
        $users = $this->getUsersForCurrentUser(['userInfo']);
        
        // Get current user to filter departments by admin
        $auth = $this->getCurrentUser();
        $currentUser = $auth['user'];
        $isSupervisor = $auth['isSupervisor'];
        
        // Get departments with user counts
        if ($currentUser && $currentUser->isAdmin()) {
            // Admin sees their own departments
            $departments = \App\Models\Department::where('admin_id', $currentUser->id)
                ->where('is_active', true)
                ->withCount(['userInfos' => function($query) use ($users) {
                    $query->whereIn('user_id', $users->pluck('id'));
                }])
                ->get();
        } elseif ($isSupervisor && $currentUser && $currentUser->admin_id) {
            // Supervisor sees departments from their assigned admin
            $departments = \App\Models\Department::where('admin_id', $currentUser->admin_id)
                ->where('is_active', true)
                ->withCount(['userInfos' => function($query) use ($users) {
                    $query->whereIn('user_id', $users->pluck('id'));
                }])
                ->get();
        } elseif ($currentUser && $currentUser->userInfo && $currentUser->userInfo->admin_id) {
            // User sees departments from their assigned admin
            $departments = \App\Models\Department::where('admin_id', $currentUser->userInfo->admin_id)
                ->where('is_active', true)
                ->withCount(['userInfos' => function($query) use ($users) {
                    $query->whereIn('user_id', $users->pluck('id'));
                }])
                ->get();
        } else {
            $departments = collect(); // Empty collection if no admin assigned
        }
        
        $departments = $departments->map(function($dept) {
                return [
                    'id' => $dept->id,
                    'name' => $dept->name,
                    'code' => $dept->code,
                    'count' => $dept->user_infos_count
                ];
            });
        
        return view('hrm::departments.index', compact('departments', 'users'));
    }

    public function attendance()
    {
        // Check supervisor permissions for HRM module
        if (!$this->checkHRMPermission('can_view_reports')) {
            return redirect()->back()->with('error', 'You do not have permission to view attendance.');
        }
        
        $users = $this->getUsersForCurrentUser(['userInfo.department']);
        
        return view('hrm::attendance.index', compact('users'));
    }

    public function payroll()
    {
        // Check supervisor permissions for HRM module
        if (!$this->checkHRMPermission('can_view_reports')) {
            return redirect()->back()->with('error', 'You do not have permission to view payroll.');
        }
        
        $users = $this->getUsersForCurrentUser(['userInfo.department']);
        
        return view('hrm::payroll.index', compact('users'));
    }

    public function createUser()
    {
        // Check supervisor permissions for HRM module
        if (!$this->checkHRMPermission('can_create_users')) {
            return redirect()->back()->with('error', 'You do not have permission to create users.');
        }
        
        // Get all modules for selection
        $modules = \App\Models\Module::all();
        
        // Get departments for selection
        $auth = $this->getCurrentUser();
        $currentUser = $auth['user'];
        $isSupervisor = $auth['isSupervisor'];
        
        if ($currentUser && $currentUser->isAdmin()) {
            // Admin sees their own departments
            $departments = \App\Models\Department::where('admin_id', $currentUser->id)
                ->where('is_active', true)
                ->orderBy('name')
                ->get();
        } elseif ($isSupervisor && $currentUser && $currentUser->admin_id) {
            // Supervisor sees departments from their assigned admin
            $departments = \App\Models\Department::where('admin_id', $currentUser->admin_id)
                ->where('is_active', true)
                ->orderBy('name')
                ->get();
        } else {
            $departments = collect(); // Empty collection if no admin assigned
        }
        
        // Get user types for selection (only User type)
        $userTypes = \App\Models\UserType::where('name', 'User')->get();
        
        // Get current user to determine what roles they can create
        $auth = $this->getCurrentUser();
        $currentUser = $auth['user'];
        $isSupervisor = $auth['isSupervisor'];
        
        $availableRoles = [];
        
        // All users (admin, supervisor, user) can only create Users
        $availableRoles = [
            ['id' => 3, 'name' => 'User', 'description' => 'Regular system user']
        ];
        
        return view('hrm::users.create', compact('modules', 'departments', 'userTypes', 'availableRoles'));
    }

    public function storeUser(Request $request)
    {
        // Check supervisor permissions for HRM module
        if (!$this->checkHRMPermission('can_create_users')) {
            return redirect()->back()->with('error', 'You do not have permission to create users.');
        }
        
        $auth = $this->getCurrentUser();
        $currentUser = $auth['user'];
        $isSupervisor = $auth['isSupervisor'];
        
        // Ensure we have valid created_by values
        if (!$currentUser || !$currentUser->id) {
            return redirect()->back()->with('error', 'Invalid user session. Please login again.');
        }

        // Log the request data for debugging
        \Log::info('User creation request data', [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'role_id' => $request->role_id,
            'modules' => $request->modules,
            'permissions' => $request->permissions
        ]);

        try {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
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
                'role_id' => 'required|in:3,4',
                'modules' => 'required|array|min:1',
                'modules.*' => 'exists:modules,id',
                'permissions' => 'nullable|array',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('User creation validation failed', [
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        }
        
        // Get the SuperAdmin ID - try multiple methods
        $superAdminId = null;
        
        // Method 1: Get from current user's user_info
        if ($currentUser->userInfo && $currentUser->userInfo->superadmin_id) {
            $superAdminId = $currentUser->userInfo->superadmin_id;
        }
        // Method 2: If current user is SuperAdmin (role_id = 1), use their own ID
        elseif ($currentUser->role_id == 1) {
            $superAdminId = $currentUser->id;
        }
        // Method 3: Find any SuperAdmin in the system
        else {
        $superAdmin = User::where('role_id', 1)->first();
            $superAdminId = $superAdmin ? $superAdmin->id : null;
        }
        
        // Determine created_by values
        $createdByType = $isSupervisor ? 'supervisor' : 'admin';
        $createdById = $currentUser->id;
        
        // Validate created_by values are not null
        if (empty($createdByType) || empty($createdById)) {
            return redirect()->back()->with('error', 'Unable to determine user creator. Please try again.');
        }

            // Generate a secure random password
            $generatedPassword = $this->generateSecurePassword();

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
                'password' => bcrypt($generatedPassword), // Use generated secure password
                'role_id' => $request->role_id, // Use selected role (3=User, 4=Supervisor)
                'is_approved' => true,
                'admin_id' => $isSupervisor ? $currentUser->admin_id : $currentUser->id,
                'superadmin_id' => $superAdminId, // Set superadmin_id in users table
            ]);

        // Create user info with additional fields
        $user->userInfo()->create([
            'admin_id' => $isSupervisor ? $currentUser->admin_id : $currentUser->id,
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
            'created_by_type' => $createdByType,
            'created_by_id' => $createdById
        ]);

        // Assign modules to the user
        if ($request->has('modules') && is_array($request->modules)) {
            foreach ($request->modules as $moduleId) {
                $module = \App\Models\Module::find($moduleId);
                if ($module) {
                    // Attach module to user with permissions
                    $permissions = [];
                    if ($request->has('permissions') && isset($request->permissions[$moduleId])) {
                        $permissions = $request->permissions[$moduleId];
                    }
                    
                    // Debug: Log what permissions are being received
                    \Log::info('Processing permissions for module', [
                        'user_id' => $user->id,
                        'module_id' => $moduleId,
                        'module_name' => $module->name,
                        'received_permissions' => $permissions,
                        'all_permissions_request' => $request->permissions
                    ]);
                    
                    $user->modules()->attach($moduleId, [
                        'can_view_reports' => in_array('view_reports', $permissions),
                        'can_create_users' => in_array('create_users', $permissions),
                        'can_edit_users' => in_array('edit_users', $permissions),
                        'can_delete_users' => in_array('delete_users', $permissions),
                        'can_reset_passwords' => in_array('reset_passwords', $permissions),
                        'can_assign_modules' => in_array('assign_modules', $permissions),
                        'can_mark_salary_paid' => in_array('mark_salary_paid', $permissions),
                        'can_mark_salary_pending' => in_array('mark_salary_pending', $permissions),
                        'can_view_salary_data' => in_array('view_salary_data', $permissions),
                        'can_manage_salary_payments' => in_array('manage_salary_payments', $permissions),
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                    
                    \Log::info('Module assigned to user', [
                        'user_id' => $user->id,
                        'module_id' => $moduleId,
                        'module_name' => $module->name,
                        'permissions' => $permissions
                    ]);
                }
            }
        }

        // Send welcome email to the new user
        try {
            $adminName = $isSupervisor ? 
                ($currentUser->admin ? $currentUser->admin->full_name : 'Supervisor') : 
                $currentUser->full_name;
            
            \Mail::to($user->email)->send(new \App\Mail\UserRegisteredMail(
                $user, 
                $generatedPassword, // Use generated secure password
                $adminName
            ));
            
            \Log::info('Welcome email sent to new user', [
                'user_id' => $user->id,
                'email' => $user->email,
                'admin_name' => $adminName
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to send welcome email', [
                'user_id' => $user->id,
                'email' => $user->email,
                'error' => $e->getMessage()
            ]);
        }

        return redirect()->route('hrm.users.index')->with('success', 'User created successfully! Welcome email sent.');
    }

    /**
     * Generate a secure random password
     */
    private function generateSecurePassword($length = 12)
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

    public function users()
    {
        try {
            // Get current user directly
            $auth = $this->getCurrentUser();
            $currentUser = $auth['user'];
            $isSupervisor = $auth['isSupervisor'];
            
            if (!$currentUser) {
                return view('hrm::users.index', [
                    'users' => collect(),
                    'totalUsers' => 0,
                    'activeUsers' => 0,
                    'inactiveUsers' => 0,
                    'departments' => 0
                ]);
            }
            
            // Build query directly based on user type
        if ($currentUser->isAdmin() || $currentUser->isSuperAdmin()) {
            $users = User::where('admin_id', $currentUser->id)
                    ->with(['userInfo.department'])
                        ->get();
            } elseif ($currentUser->isSupervisor() || $isSupervisor) {
                $users = User::where('admin_id', $currentUser->admin_id)
                    ->with(['userInfo.department'])
                        ->get();
        } else {
            $users = collect();
        }
        
        // Calculate statistics
        $totalUsers = $users->count();
        $activeUsers = $users->where('is_approved', true)->count();
        $inactiveUsers = $users->where('is_approved', false)->count();
            $departments = $users->pluck('userInfo.department.name')->filter()->unique()->count();
            
            // Set permission variables for the view
            $canCreateUsers = $this->checkHRMPermission('can_create_users');
            $canEditUsers = $this->checkHRMPermission('can_edit_users');
            $canDeleteUsers = $this->checkHRMPermission('can_delete_users');
            $canViewReports = $this->checkHRMPermission('can_view_reports');
            
            return view('hrm::users.index', compact(
                'users', 
                'totalUsers', 
                'activeUsers', 
                'inactiveUsers', 
                'departments',
                'canCreateUsers',
                'canEditUsers', 
                'canDeleteUsers',
                'canViewReports'
            ));
            
        } catch (\Exception $e) {
            \Log::error('Error in users method: ' . $e->getMessage());
            return view('hrm::users.index', [
                'users' => collect(),
                'totalUsers' => 0,
                'activeUsers' => 0,
                'inactiveUsers' => 0,
                'departments' => 0
            ]);
        }
    }

    public function viewUser($id)
    {
        // Check supervisor permissions for HRM module
        if (!$this->checkHRMPermission('can_view_reports')) {
            return redirect()->back()->with('error', 'You do not have permission to view user details.');
        }
        
        $users = $this->getUsersForCurrentUser(['userInfo.department']);
        $user = $users->where('id', $id)->first();
        
        if (!$user) {
            return redirect()->back()->with('error', 'User not found or you do not have permission to view this user.');
        }
        
        return view('hrm::users.view', compact('user'));
    }

    public function editUser($id)
    {
        // Check supervisor permissions for HRM module
        if (!$this->checkHRMPermission('can_edit_users')) {
            return redirect()->back()->with('error', 'You do not have permission to edit users.');
        }
        
        // Get current user to check admin_id
        $auth = $this->getCurrentUser();
        $currentUser = $auth['user'];
        $isSupervisor = $auth['isSupervisor'];
        
        // Load the user with all relationships, but only if they belong to current admin
        $user = \App\Models\User::with(['userInfo.department', 'modules'])
            ->where('id', $id)
            ->where(function($query) use ($currentUser, $isSupervisor) {
                if ($currentUser->isAdmin() || $currentUser->isSuperAdmin()) {
                    $query->where('admin_id', $currentUser->id);
                } elseif ($isSupervisor) {
                    $query->where('admin_id', $currentUser->admin_id);
                }
            })
            ->first();
        
        if (!$user) {
            return redirect()->back()->with('error', 'User not found or you do not have permission to edit this user.');
        }
        
        // Debug: Log what's being loaded
        \Log::info('User edit debug for user ID: ' . $user->id, [
            'user_name' => $user->first_name . ' ' . $user->last_name,
            'modules_count' => $user->modules->count(),
            'raw_database' => \DB::table('user_modules')->where('user_id', $user->id)->get()->toArray(),
            'user_modules' => $user->modules->map(function($module) {
                return [
                    'module_id' => $module->id,
                    'module_name' => $module->name,
                    'pivot_exists' => $module->pivot ? true : false,
                    'pivot_data' => $module->pivot ? $module->pivot->toArray() : null
                ];
            })->toArray()
        ]);
        
        
        // Get departments for selection (only admin's departments)
        if ($currentUser && $currentUser->isAdmin()) {
            $departments = \App\Models\Department::where('admin_id', $currentUser->id)
                ->where('is_active', true)
                ->orderBy('name')
                ->get();
        } else {
            $departments = collect(); // Empty collection if not admin
        }
        $modules = \App\Models\Module::all();
        
        return view('hrm::users.edit', compact('user', 'departments', 'modules'));
    }

    public function updateUser(Request $request, $id)
    {
        // Check supervisor permissions for HRM module
        if (!$this->checkHRMPermission('can_edit_users')) {
            return redirect()->back()->with('error', 'You do not have permission to update users.');
        }
        
        $users = $this->getUsersForCurrentUser();
        $user = $users->where('id', $id)->first();
        
        if (!$user) {
            return redirect()->back()->with('error', 'User not found or you do not have permission to update this user.');
        }

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
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
            'job_title' => 'nullable|string|max:255',
            'department_id' => 'nullable|exists:departments,id',
            'company' => 'nullable|string|max:255',
            'modules' => 'nullable|array',
            'modules.*' => 'exists:modules,id',
            'permissions' => 'nullable|array',
            'permissions.*' => 'array'
        ]);

        // Update user basic info
        $user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
        ]);

        // Update user info
        if ($user->userInfo) {
            $user->userInfo->update([
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
            ]);
        } else {
            $user->userInfo()->create([
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
            ]);
        }

        // Handle modules and permissions if provided
        if ($request->has('modules') && is_array($request->modules)) {
            // Remove existing module assignments
            $user->modules()->detach();
            
            // Add new module assignments with permissions
            foreach ($request->modules as $moduleId) {
                $permissions = $request->permissions[$moduleId] ?? [];
                
                $user->modules()->attach($moduleId, [
                    'can_create_users' => in_array('create_users', $permissions),
                    'can_edit_users' => in_array('edit_users', $permissions),
                    'can_delete_users' => in_array('delete_users', $permissions),
                    'can_reset_passwords' => in_array('reset_passwords', $permissions),
                    'can_assign_modules' => in_array('assign_modules', $permissions),
                    'can_view_reports' => in_array('view_reports', $permissions),
                    'can_mark_salary_paid' => in_array('mark_salary_paid', $permissions),
                    'can_mark_salary_pending' => in_array('mark_salary_pending', $permissions),
                    'can_view_salary_data' => in_array('view_salary_data', $permissions),
                    'can_manage_salary_payments' => in_array('manage_salary_payments', $permissions),
                ]);
            }
        }

        return redirect()->route('hrm.employees')->with('success', 'User updated successfully.');
    }

    public function deleteUser($id)
    {
        // Check supervisor permissions for HRM module
        if (!$this->checkHRMPermission('can_delete_users')) {
            return redirect()->back()->with('error', 'You do not have permission to delete users.');
        }
        
        $users = $this->getUsersForCurrentUser();
        $user = $users->where('id', $id)->first();
        
        if (!$user) {
            return redirect()->back()->with('error', 'User not found or you do not have permission to delete this user.');
        }

        // Delete user info first
        if ($user->userInfo) {
            $user->userInfo->delete();
        }
        
        // Delete user
        $user->delete();

        return redirect()->route('hrm.employees')->with('success', 'User deleted successfully.');
    }
}
