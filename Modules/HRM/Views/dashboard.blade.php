@extends('hrm::layouts.hrm-master')

@section('title', 'HRM Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">HRM Dashboard</h1>
            <p class="text-muted">Human Resource Management System</p>
        </div>
    </div>

    <!-- Your Permissions Section -->
    @php
        $currentUser = null;
        $permissions = [];
        $isSupervisor = false;
        
        // Check if user is supervisor
        if (Auth::guard('supervisor')->check()) {
            $currentUser = Auth::guard('supervisor')->user();
            $isSupervisor = true;
            
            // Get HRM module permissions (module ID 1)
            $modulePermission = $currentUser->permissions()->where('module_id', 1)->first();
            if ($modulePermission) {
                $permissions = [
                    'can_create_users' => $modulePermission->can_create_users,
                    'can_edit_users' => $modulePermission->can_edit_users,
                    'can_delete_users' => $modulePermission->can_delete_users,
                    'can_reset_passwords' => $modulePermission->can_reset_passwords,
                    'can_assign_modules' => $modulePermission->can_assign_modules,
                    'can_view_reports' => $modulePermission->can_view_reports,
                ];
            }
        } else {
            // Check if user is regular user
            $currentUser = Auth::user();
            if ($currentUser) {
                // Get HRM module permissions (module ID 1)
                $modulePermission = $currentUser->modules()->where('module_id', 1)->first();
                if ($modulePermission) {
                    $permissions = [
                        'can_create_users' => $modulePermission->pivot->can_create_users ?? false,
                        'can_edit_users' => $modulePermission->pivot->can_edit_users ?? false,
                        'can_delete_users' => $modulePermission->pivot->can_delete_users ?? false,
                        'can_reset_passwords' => $modulePermission->pivot->can_reset_passwords ?? false,
                        'can_assign_modules' => $modulePermission->pivot->can_assign_modules ?? false,
                        'can_view_reports' => $modulePermission->pivot->can_view_reports ?? false,
                    ];
                }
            }
        }
        
        // Filter to only show permissions that are enabled
        $enabledPermissions = array_filter($permissions, function($value) {
            return $value === true;
        });
    @endphp

    @if($currentUser && !empty($enabledPermissions))
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-left-info shadow">
                <div class="card-header bg-info text-white">
                    <div class="d-flex align-items-center">
                        <i class="bx bx-shield-check me-2"></i>
                        <h6 class="mb-0">Your HRM Permissions</h6>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($enabledPermissions as $permission => $enabled)
                            @if($enabled)
                                <div class="col-md-4 col-sm-6 mb-2">
                                    <div class="d-flex align-items-center">
                                        <i class="bx bx-check-circle text-success me-2"></i>
                                        <span class="text-muted">
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
                                                @default
                                                    {{ ucwords(str_replace('_', ' ', $permission)) }}
                                            @endswitch
                                        </span>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Employees</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalEmployees ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Active Employees</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $activeEmployees ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Departments</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $departments ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-building fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total Salary</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">${{ number_format($totalSalary ?? 0, 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('hrm.employees') }}" class="btn btn-primary btn-block">
                                <i class="fas fa-users"></i> Manage Employees
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('hrm.attendance') }}" class="btn btn-success btn-block">
                                <i class="fas fa-clock"></i> Attendance
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('hrm.departments') }}" class="btn btn-info btn-block">
                                <i class="fas fa-building"></i> Departments
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('hrm.payroll') }}" class="btn btn-warning btn-block">
                                <i class="fas fa-money-bill"></i> Payroll
                            </a>
                        </div>
                        @if(hasPermission(1, 'can_view_reports') || hasPermission(1, 'can_edit_users') || hasPermission(1, 'can_delete_users'))
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('hrm.users.index') }}" class="btn btn-secondary btn-block">
                                <i class="fas fa-user-friends"></i> View Users
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Activities</h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            New employee added
                            <small class="text-muted">2 hours ago</small>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            Leave request submitted
                            <small class="text-muted">4 hours ago</small>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            Attendance marked
                            <small class="text-muted">6 hours ago</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Users Table Section -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Employees</h6>
                    <small class="text-muted">Showing employees under your management</small>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered text-nowrap">
                            <thead>
                                <tr>
                                    <th class="border-bottom-0">Name</th>
                                    <th class="border-bottom-0">Email</th>
                                    <th class="border-bottom-0">Department</th>
                                    <th class="border-bottom-0">Salary</th>
                                    <th class="border-bottom-0">Status</th>
                                    <th class="border-bottom-0">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users ?? [] as $user)
                                <tr>
                                    <td>{{ $user->full_name ?? 'N/A' }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @if($user->userInfo && $user->userInfo->department_id)
                                            @php
                                                // Try to get department via relationship first
                                                $department = $user->userInfo->department;
                                                if (!$department) {
                                                    // Fallback to direct lookup
                                                    $department = \App\Models\Department::find($user->userInfo->department_id);
                                                }
                                            @endphp
                                            {{ $department ? $department->name : 'N/A' }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>
                                        @if($user->userInfo && $user->userInfo->salary > 0)
                                            ${{ number_format($user->userInfo->salary, 2) }}
                                        @else
                                            <span class="text-muted">No salary set</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($user->is_approved)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-warning">Pending</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(hasPermission(1, 'can_edit_users'))
                                            <a href="{{ route('hrm.users.edit', $user->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                        @else
                                            <button class="btn btn-warning btn-sm" onclick="showPermissionError('edit users')">Edit</button>
                                        @endif
                                        
                                        @if(hasPermission(1, 'can_view_reports'))
                                            <a href="{{ route('hrm.users.view', $user->id) }}" class="btn btn-info btn-sm">View</a>
                                        @else
                                            <button class="btn btn-info btn-sm" onclick="showPermissionError('view reports')">View</button>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">No employees found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
