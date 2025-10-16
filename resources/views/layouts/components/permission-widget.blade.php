@php
    $currentUser = null;
    $permissions = [];
    $isSupervisor = false;
    $currentModule = null;
    
    // Check if user is supervisor
    if (Auth::guard('supervisor')->check()) {
        $currentUser = Auth::guard('supervisor')->user();
        $isSupervisor = true;
        
        // Get supervisor permissions for current module
        // Try to detect current module from route
        $routeName = request()->route()->getName();
        if (str_contains($routeName, 'hrm')) {
            $currentModule = 1; // HRM module ID
        } elseif (str_contains($routeName, 'finance')) {
            $currentModule = 2; // Finance module ID
        } elseif (str_contains($routeName, 'support')) {
            $currentModule = 3; // Support module ID
        }
        
        if ($currentModule) {
            $modulePermission = $currentUser->permissions()->where('module_id', $currentModule)->first();
            if ($modulePermission) {
                $permissions = [
                    'can_create_users' => $modulePermission->can_create_users,
                    'can_edit_users' => $modulePermission->can_edit_users,
                    'can_delete_users' => $modulePermission->can_delete_users,
                    'can_reset_passwords' => $modulePermission->can_reset_passwords,
                    'can_assign_modules' => $modulePermission->can_assign_modules,
                    'can_view_reports' => $modulePermission->can_view_reports,
                    'can_mark_salary_paid' => $modulePermission->can_mark_salary_paid ?? false,
                    'can_mark_salary_pending' => $modulePermission->can_mark_salary_pending ?? false,
                    'can_view_salary_data' => $modulePermission->can_view_salary_data ?? false,
                    'can_manage_salary_payments' => $modulePermission->can_manage_salary_payments ?? false,
                ];
            }
        }
    } else {
        // Check if user is regular user
        $currentUser = Auth::user();
        if ($currentUser) {
            // Get user permissions for current module
            // Try to detect current module from route
            $routeName = request()->route()->getName();
            if (str_contains($routeName, 'hrm')) {
                $currentModule = 1; // HRM module ID
            } elseif (str_contains($routeName, 'finance')) {
                $currentModule = 2; // Finance module ID
            } elseif (str_contains($routeName, 'support')) {
                $currentModule = 3; // Support module ID
            }
            
            if ($currentModule) {
                $modulePermission = $currentUser->modules()->where('module_id', $currentModule)->first();
                if ($modulePermission) {
                    $permissions = [
                        'can_create_users' => $modulePermission->pivot->can_create_users ?? false,
                        'can_edit_users' => $modulePermission->pivot->can_edit_users ?? false,
                        'can_delete_users' => $modulePermission->pivot->can_delete_users ?? false,
                        'can_reset_passwords' => $modulePermission->pivot->can_reset_passwords ?? false,
                        'can_assign_modules' => $modulePermission->pivot->can_assign_modules ?? false,
                        'can_view_reports' => $modulePermission->pivot->can_view_reports ?? false,
                        'can_mark_salary_paid' => $modulePermission->pivot->can_mark_salary_paid ?? false,
                        'can_mark_salary_pending' => $modulePermission->pivot->can_mark_salary_pending ?? false,
                        'can_view_salary_data' => $modulePermission->pivot->can_view_salary_data ?? false,
                        'can_manage_salary_payments' => $modulePermission->pivot->can_manage_salary_payments ?? false,
                    ];
                }
            }
        }
    }
    
    // Filter to only show permissions that are enabled
    $enabledPermissions = array_filter($permissions, function($value) {
        return $value === true || $value === 1 || $value === '1';
    });
@endphp

@if($currentUser && !empty($enabledPermissions))
<div class="permission-widget position-fixed" style="top: 20px; right: 20px; z-index: 1000;">
    <div class="card custom-card shadow-lg" style="max-width: 300px;">
        <div class="card-header bg-primary text-white">
            <div class="d-flex align-items-center">
                <i class="bx bx-shield-check me-2"></i>
                <h6 class="mb-0">Your Permissions</h6>
            </div>
        </div>
        <div class="card-body p-2">
            <div class="row g-2">
                @foreach($enabledPermissions as $permission => $enabled)
                    @if($enabled)
                        <div class="col-12">
                            <div class="d-flex align-items-center">
                                <i class="bx bx-check-circle text-success me-2"></i>
                                <small class="text-muted">
                                    @switch($permission)
                                        @case('can_create_users')
                                            Create Users
                                            @break
                                        @case('can_edit_users')
                                            Edit Users
                                            @break
                                        @case('can_delete_users')
                                            Delete Users
                                            @break
                                        @case('can_reset_passwords')
                                            Reset Passwords
                                            @break
                                        @case('can_assign_modules')
                                            Assign Modules
                                            @break
                                        @case('can_view_reports')
                                            View Reports
                                            @break
                                        @case('can_mark_salary_paid')
                                            Mark Salary Paid
                                            @break
                                        @case('can_mark_salary_pending')
                                            Mark Salary Pending
                                            @break
                                        @case('can_view_salary_data')
                                            View Salary Data
                                            @break
                                        @case('can_manage_salary_payments')
                                            Manage Salary Payments
                                            @break
                                        @default
                                            {{ ucwords(str_replace('_', ' ', $permission)) }}
                                    @endswitch
                                </small>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</div>
@endif
