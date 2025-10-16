<?php

namespace Modules\FINANCE\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Module;
use App\Models\Department;
use App\Models\UserType;
use App\Models\SalaryPayment;
use App\Models\SalaryPaymentHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class FINANCEController extends Controller
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
                'email' => $supervisorUser->email
            ]);
            return ['user' => $supervisorUser, 'isSupervisor' => true];
        }
        
        // If no supervisor, check web guard
        $webUser = Auth::user();
        if ($webUser) {
            \Log::info('Web user detected in getCurrentUser', [
                'user_id' => $webUser->id,
                'email' => $webUser->email
            ]);
            return ['user' => $webUser, 'isSupervisor' => false];
        }
        
        // No user authenticated
        \Log::warning('No user authenticated in getCurrentUser');
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
            \Log::info('Finance getUsersForCurrentUser debug', [
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
            \Log::error('Error in Finance getUsersForCurrentUser: ' . $e->getMessage());
            return collect();
        }
    }
    
    private function getAdminUsers($currentUser, $withRelations = [])
    {
        try {
            \Log::info('Using admin query for user: ' . $currentUser->id);
            
            // Use a direct query approach to avoid any issues
            $adminId = $currentUser->id;
            
            $query = User::query();
            $query->where('admin_id', $adminId);
            
            if (!empty($withRelations)) {
                $query->with($withRelations);
            }
            
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
            \Log::info('Using supervisor query for user: ' . $currentUser->id);
            
            // Supervisors see users under their admin
            $adminId = $currentUser->admin_id;
            
            $query = User::query();
            $query->where('admin_id', $adminId);
            
            if (!empty($withRelations)) {
                $query->with($withRelations);
            }
            
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
     * Check if current user has permission for FINANCE module
     */
    private function checkFINANCEPermission($permission)
    {
        $auth = $this->getCurrentUser();
        $currentUser = $auth['user'];
        $isSupervisor = $auth['isSupervisor'];
        
        \Log::info('FINANCE Permission Check', [
            'permission' => $permission,
            'user_id' => $currentUser ? $currentUser->id : null,
            'is_supervisor' => $isSupervisor,
            'user_class' => $currentUser ? get_class($currentUser) : null
        ]);
        
        // Admin and SuperAdmin always have access
        if ($currentUser && ($currentUser->isAdmin() || $currentUser->isSuperAdmin())) {
            \Log::info('Admin/SuperAdmin access granted for FINANCE', [
                'user_id' => $currentUser->id,
                'role_id' => $currentUser->role_id,
                'is_admin' => $currentUser->isAdmin(),
                'is_superadmin' => $currentUser->isSuperAdmin()
            ]);
            return true;
        }
        
        // Check supervisor permissions for FINANCE module (ID: 2)
        if ($isSupervisor && $currentUser) {
            // Log supervisor's modules and permissions
            $supervisorModules = $currentUser->modules()->get();
            \Log::info('Supervisor modules', [
                'modules' => $supervisorModules->pluck('name', 'id')->toArray(),
                'finance_module_exists' => $supervisorModules->where('id', 2)->isNotEmpty()
            ]);
            
            $hasPermission = $currentUser->hasPermission(2, $permission);
            \Log::info('Supervisor FINANCE permission check', [
                'has_permission' => $hasPermission,
                'module_id' => 2,
                'permission' => $permission,
                'supervisor_id' => $currentUser->id
            ]);
            return $hasPermission;
        }
        
        // Check user permissions for FINANCE module (ID: 2)
        if ($currentUser && $currentUser->isUser()) {
            $hasPermission = $currentUser->hasPermission(2, $permission);
            \Log::info('User FINANCE permission check', [
                'has_permission' => $hasPermission,
                'module_id' => 2,
                'permission' => $permission,
                'user_id' => $currentUser->id
            ]);
            return $hasPermission;
        }
        
        \Log::warning('No permission granted for FINANCE', [
            'user_id' => $currentUser ? $currentUser->id : null,
            'is_supervisor' => $isSupervisor
        ]);
        return false;
    }

    public function dashboard()
    {
        // Debug: Log current user info using getCurrentUser like HRM
        $auth = $this->getCurrentUser();
        $currentUser = $auth['user'];
        $isSupervisor = $auth['isSupervisor'];
        
        \Log::info('Finance Dashboard Access Attempt', [
            'user_id' => $currentUser ? $currentUser->id : null,
            'is_supervisor' => $isSupervisor,
            'user_type' => $currentUser ? get_class($currentUser) : null
        ]);
        
        // Check Finance permissions - allow access if user has any Finance permissions
        $hasAnyFinancePermission = $this->checkFINANCEPermission('can_view_reports') || 
                                  $this->checkFINANCEPermission('can_mark_salary_paid') || 
                                  $this->checkFINANCEPermission('can_view_salary_data') || 
                                  $this->checkFINANCEPermission('can_manage_salary_payments');
        
        if (!$hasAnyFinancePermission) {
            \Log::warning('Finance permission denied', [
                'user_id' => $currentUser ? $currentUser->id : null,
                'is_supervisor' => $isSupervisor
            ]);
            return redirect()->back()->with('error', 'You do not have permission to view Finance dashboard.');
        }
        
        // Use the same user filtering logic as HRM
        $users = $this->getUsersForCurrentUser(['userInfo', 'userInfo.department']);
        
        // Get salary payment statistics
        $totalUsers = $users->count();
        $totalSalary = $users->sum(function($user) {
            return $user->userInfo ? $user->userInfo->salary : 0;
        });
        
        // Get user IDs for salary payment filtering
        $userIds = $users->pluck('id')->toArray();
        
        // Calculate paid salaries from SalaryPayment records
        $paidSalaries = SalaryPayment::whereIn('user_id', $userIds)
            ->where('status', 'paid')
            ->sum('amount');
            
        // Calculate pending salaries - users with salary but no paid payment record
        $pendingSalaries = 0;
        foreach ($users as $user) {
            if ($user->userInfo && $user->userInfo->salary > 0) {
                // Check if user has any paid salary payment
                $hasPaidPayment = SalaryPayment::where('user_id', $user->id)
                    ->where('status', 'paid')
                    ->exists();
                
                // If no paid payment, add their salary to pending
                if (!$hasPaidPayment) {
                    $pendingSalaries += $user->userInfo->salary;
                }
            }
        }
        
        // Check for overdue payments
        $overduePayments = SalaryPayment::whereIn('user_id', $userIds)
            ->where('status', 'pending')
            ->where('next_payment_date', '<', now())
            ->with('user')
            ->get();
        
        // Get users with overdue payments for alerts
        $overdueUsers = $overduePayments->pluck('user')->unique();
        
        // Get payment history for the current month - FILTERED BY ADMIN
        $currentMonth = now()->format('Y-m');
        $currentYear = now()->format('Y');
        
        // Get monthly stats for current admin's users only
        $monthlyStats = $this->getMonthlyStatsForAdmin($currentYear, $currentMonth, $userIds);
        
        // Get recent payments for current admin's users only
        $recentPayments = SalaryPaymentHistory::where('payment_year', $currentYear)
            ->where('payment_month', $currentMonth)
            ->whereIn('user_id', $userIds) // CRITICAL: Filter by admin's users only
            ->with(['user', 'paidBy'])
            ->orderBy('paid_at', 'desc')
            ->limit(10)
            ->get();
        
        return view('finance::dashboard', compact(
            'users', 'totalUsers', 'totalSalary', 'paidSalaries', 'pendingSalaries', 
            'overduePayments', 'overdueUsers', 'monthlyStats', 'recentPayments'
        ));
    }
    
    /**
     * Get monthly stats for current admin's users only
     */
    private function getMonthlyStatsForAdmin($year, $month, $userIds)
    {
        // Parse month string (e.g., "2025-10" -> year=2025, month=10)
        $monthParts = explode('-', $month);
        $year = (int)$year;
        $monthNum = (int)$monthParts[1];
        
        $startOfMonth = \Carbon\Carbon::createFromDate($year, $monthNum, 1)->startOfMonth();
        $endOfMonth = \Carbon\Carbon::createFromDate($year, $monthNum, 1)->endOfMonth();
        
        // Get payments for current admin's users only
        $payments = SalaryPaymentHistory::whereBetween('paid_at', [$startOfMonth, $endOfMonth])
            ->whereIn('user_id', $userIds) // CRITICAL: Filter by admin's users only
            ->get();
            
        $totalPayments = $payments->count();
        $paidAmount = $payments->where('status', 'paid')->sum('amount');
        $pendingAmount = $payments->where('status', 'pending')->sum('amount');
        $overdueAmount = $payments->where('status', 'overdue')->sum('amount');
        
        return compact('totalPayments', 'paidAmount', 'pendingAmount', 'overdueAmount');
    }

    public function accounts()
    {
        // Get current authenticated user
        $currentUser = Auth::user();
        
        if (!$currentUser) {
            return redirect()->route('login')->with('error', 'Please login to access accounts.');
        }
        
        $users = User::with(['userInfo', 'userInfo.department'])->get();
        
        return view('finance::accounts.index', compact('users'));
    }

    public function transactions()
    {
        // Get current authenticated user
        $currentUser = Auth::user();
        
        if (!$currentUser) {
            return redirect()->route('login')->with('error', 'Please login to access transactions.');
        }
        
        $users = User::with(['userInfo.department'])->get();
        
        return view('finance::transactions.index', compact('users'));
    }

    public function budgets()
    {
        // Get current authenticated user
        $currentUser = Auth::user();
        
        if (!$currentUser) {
            return redirect()->route('login')->with('error', 'Please login to access budgets.');
        }
        
        $users = User::with(['userInfo.department'])->get();
        
        return view('finance::budgets.index', compact('users'));
    }

    public function reports()
    {
        // Get current authenticated user
        $currentUser = Auth::user();
        
        if (!$currentUser) {
            return redirect()->route('login')->with('error', 'Please login to access reports.');
        }
        
        $users = User::with(['userInfo.department'])->get();
        
        return view('finance::reports.index', compact('users'));
    }

    /**
     * Display salary management page
     */
    public function salaries()
    {
        // Get current authenticated user
        $currentUser = Auth::user();
        
        if (!$currentUser) {
            return redirect()->route('login')->with('error', 'Please login to access salary management.');
        }
        
        // For admins and superadmins, get all users with salary info
        $users = User::with(['userInfo'])->whereHas('userInfo', function($query) {
            $query->whereNotNull('salary');
        })->get();
        
        // Get salary payments for these users
        $salaryPayments = SalaryPayment::whereIn('user_id', $users->pluck('id'))
            ->with(['user', 'paidBy'])
            ->get();
        
        // Calculate statistics
        $totalSalary = $users->sum(function($user) {
            return $user->userInfo ? $user->userInfo->salary : 0;
        });
        $totalPaid = $salaryPayments->where('status', 'paid')->sum('amount');
        
        // Calculate pending salaries - users with salary but no paid payment record
        $totalPending = 0;
        foreach ($users as $user) {
            if ($user->userInfo && $user->userInfo->salary > 0) {
                // Check if user has any paid salary payment
                $hasPaidPayment = SalaryPayment::where('user_id', $user->id)
                    ->where('status', 'paid')
                    ->exists();
                
                // If no paid payment, add their salary to pending
                if (!$hasPaidPayment) {
                    $totalPending += $user->userInfo->salary;
                }
            }
        }
        
        return view('finance::salaries.index', compact('users', 'salaryPayments', 'totalSalary', 'totalPaid', 'totalPending'));
    }

    /**
     * Mark salary as paid
     */
    public function markPaid(Request $request, $userId)
    {
        \Log::info('=== MARK PAID METHOD CALLED ===', [
            'user_id' => $userId,
            'request_data' => $request->all(),
            'timestamp' => now()
        ]);
        
        // Check Finance permissions - temporarily disabled for debugging
        // if (!$this->checkFINANCEPermission('can_mark_salary_paid')) {
        //     \Log::warning('Finance permission denied for mark paid');
        //     return redirect()->back()->with('error', 'You do not have permission to mark salary as paid.');
        // }
        
        // Get current authenticated user
        $currentUser = $this->getCurrentUser();
        
        if (!$currentUser['user']) {
            return redirect()->route('login')->with('error', 'Please login to mark salary as paid.');
        }
        
        $user = User::with('userInfo')->findOrFail($userId);
        
        if (!$user->userInfo || !$user->userInfo->salary) {
            return redirect()->back()->with('error', 'User does not have salary information.');
        }
        
        // Check if already paid
        $existingPayment = SalaryPayment::where('user_id', $userId)
            ->where('status', 'paid')
            ->first();
            
        if ($existingPayment) {
            return redirect()->back()->with('error', 'Salary already marked as paid.');
        }
        
        // Calculate next payment date based on user creation date
        $userCreatedDate = $user->created_at;
        $dayOfMonth = $userCreatedDate->day; // Get the day of month when user was created
        
        // Set next payment date to the same day next month
        $nextPaymentDate = now()->copy()->day($dayOfMonth)->addMonth();
        
        // If the calculated date is in the past, set it to next month
        if ($nextPaymentDate->isPast()) {
            $nextPaymentDate = $nextPaymentDate->addMonth();
        }
        
        // Determine who marked it as paid
        $paidByName = $currentUser['user']->full_name;
        if ($currentUser['isSupervisor']) {
            $paidByName = 'Supervisor: ' . $paidByName;
        } elseif ($currentUser['user']->isAdmin()) {
            $paidByName = 'Admin: ' . $paidByName;
        } elseif ($currentUser['user']->isSuperAdmin()) {
            $paidByName = 'SuperAdmin: ' . $paidByName;
        } else {
            $paidByName = 'User: ' . $paidByName;
        }
        
        // Create salary payment record
        $salaryPayment = SalaryPayment::create([
            'user_id' => $userId,
            'amount' => $user->userInfo->salary,
            'status' => 'paid',
            'paid_by' => $currentUser['user']->id,
            'paid_at' => now(),
            'next_payment_date' => $nextPaymentDate,
            'notes' => $request->notes ?? 'Salary payment marked as paid by ' . $paidByName
        ]);
        
        // Create payment history record for monthly tracking
        $paymentHistory = SalaryPaymentHistory::create([
            'user_id' => $userId,
            'amount' => $user->userInfo->salary,
            'payment_month' => now()->format('Y-m'),
            'payment_year' => now()->format('Y'),
            'status' => 'paid',
            'paid_by' => $currentUser['user']->id,
            'paid_by_type' => $currentUser['isSupervisor'] ? 'supervisor' : ($currentUser['user']->isAdmin() ? 'admin' : 'user'),
            'paid_by_name' => $currentUser['user']->full_name,
            'paid_at' => now(),
            'due_date' => now(),
            'notes' => $request->notes ?? 'Salary payment marked as paid by ' . $paidByName
        ]);
        
        \Log::info('Salary payment and history created successfully', [
            'payment_id' => $salaryPayment->id,
            'history_id' => $paymentHistory->id,
            'user_id' => $userId,
            'amount' => $salaryPayment->amount,
            'status' => $salaryPayment->status,
            'payment_month' => $paymentHistory->payment_month
        ]);
        
        return redirect()->back()->with('success', 'Salary marked as paid successfully.');
    }

    /**
     * Mark salary as pending (undo paid status)
     */
    public function markPending($userId)
    {
        // Check Finance permissions
        if (!$this->checkFINANCEPermission('can_mark_salary_pending')) {
            return redirect()->back()->with('error', 'You do not have permission to mark salary as pending.');
        }
        
        // Get current authenticated user
        $currentUser = $this->getCurrentUser();
        
        if (!$currentUser['user']) {
            return redirect()->route('login')->with('error', 'Please login to mark salary as pending.');
        }
        
        $payment = SalaryPayment::where('user_id', $userId)
            ->where('status', 'paid')
            ->first();
            
        if (!$payment) {
            return redirect()->back()->with('error', 'No paid salary found for this user.');
        }
        
        $payment->update([
            'status' => 'pending',
            'paid_by' => null,
            'paid_at' => null,
            'next_payment_date' => null
        ]);
        
        return redirect()->back()->with('success', 'Salary marked as pending successfully.');
    }

    /**
     * Display the create user form.
     * NO PERMISSION CHECKS - Accessible to all users
     */
    public function createUser()
    {
        // Get only FINANCE module (pre-selected)
        $modules = Module::where('name', 'FINANCE')->get();
        
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
        
        return view('finance::users.create', compact('modules', 'departments', 'userTypes'));
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
                    // For FINANCE module, give full permissions automatically
                    if ($module->name === 'FINANCE') {
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

        return redirect()->route('finance.users.index')->with('success', 'User created successfully! Welcome email sent.');
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
        
        return view('finance::users.index', compact('users', 'totalUsers', 'activeUsers', 'inactiveUsers'));
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
