@extends('layouts.master')

@section('title') CRM Dashboard @endsection

@section('styles')
@endsection

@section('content')

<div class="container-fluid">
    <!-- Start::row-1 -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">
                        Welcome to CRM Dashboard
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h4>Hello, {{ Auth::user()->full_name }}!</h4>
                                    @php
                                        $user = Auth::user();
                                        $roleName = $user->role_id == 1 ? 'SuperAdmin' : ($user->role_id == 2 ? 'Admin' : ($user->role_id == 3 ? 'User' : ($user->role_id == 4 ? 'Supervisor' : 'User')));
                                    @endphp
                                    <p class="text-muted">
                                        <span class="badge bg-info me-2">{{ $roleName }}</span>
                                        Access your assigned modules below:
                                    </p>
                                </div>
                                <div>
                                    @php
                                        $user = Auth::user();
                                        $completionPercentage = \App\Http\Controllers\UserController::calculateProfileCompletion($user);
                                    @endphp
                                    
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="text-end">
                                            <small class="text-muted">Profile Completion</small>
                                            <div class="progress" style="width: 100px; height: 6px;">
                                                <div class="progress-bar bg-{{ $completionPercentage >= 80 ? 'success' : ($completionPercentage >= 50 ? 'warning' : 'danger') }}" 
                                                     style="width: {{ $completionPercentage }}%"></div>
                                            </div>
                                            <small class="text-muted">{{ $completionPercentage }}%</small>
                                        </div>
                                        <a href="{{ route('user.profile') }}" class="btn btn-outline-primary">
                                            <i class="ti ti-user me-1"></i>Complete Profile
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- User Type Information Card -->
                    <div class="row mb-4">
                        <div class="col-xl-12">
                            <div class="card custom-card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <span class="avatar avatar-lg bg-info-transparent">
                                                <i class="ti ti-badge fs-24"></i>
                                            </span>
                                        </div>
                                        <div>
                                            <h5 class="mb-1">Your Role: {{ $roleName }}</h5>
                                            <p class="text-muted mb-0">You have been assigned this role by your administrator.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Module Cards -->
                    <div class="row">
                        @forelse($userModules as $userModule)
                            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-4">
                                <div class="card custom-card h-100 module-card" onclick="window.location.href='{{ \App\Helpers\DashboardHelper::getModuleDashboardRoute($userModule->module->id) }}'">
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            <span class="avatar avatar-xl bg-primary-transparent">
                                                <i class="{{ $userModule->module->icon ?? 'bx bx-package' }} fs-24"></i>
                                            </span>
                                        </div>
                                        <h5 class="card-title">{{ $userModule->module->name }}</h5>
                                        <p class="text-muted">{{ $userModule->module->description }}</p>
                                        <div class="mt-3">
                                            <span class="badge bg-success">Active</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="text-center py-5">
                                    <i class="bx bx-package fs-48 text-muted"></i>
                                    <h5 class="mt-3">No modules assigned yet</h5>
                                    <p class="text-muted">Contact your administrator to get module access</p>
                                </div>
                            </div>
                        @endforelse
                    </div>
                    
                    <!-- User Permissions Section -->
                    @if($userModules->count() > 0)
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card custom-card">
                                <div class="card-header">
                                    <div class="card-title">
                                        <i class="bx bx-shield-check me-2"></i>
                                        Your Module Permissions
                                    </div>
                                    <p class="text-muted mb-0">Here are the permissions you have for each module</p>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        @foreach($userModules as $userModule)
                                            @php
                                                $module = $userModule->module;
                                                $permissions = [];
                                                
                                                // Get permissions for this module
                                                if ($userModule->pivot) {
                                                    $permissions = [
                                                        'can_view_reports' => $userModule->pivot->can_view_reports ?? false,
                                                        'can_create_users' => $userModule->pivot->can_create_users ?? false,
                                                        'can_edit_users' => $userModule->pivot->can_edit_users ?? false,
                                                        'can_delete_users' => $userModule->pivot->can_delete_users ?? false,
                                                        'can_reset_passwords' => $userModule->pivot->can_reset_passwords ?? false,
                                                        'can_assign_modules' => $userModule->pivot->can_assign_modules ?? false,
                                                        'can_mark_salary_paid' => $userModule->pivot->can_mark_salary_paid ?? false,
                                                        'can_mark_salary_pending' => $userModule->pivot->can_mark_salary_pending ?? false,
                                                        'can_view_salary_data' => $userModule->pivot->can_view_salary_data ?? false,
                                                        'can_manage_salary_payments' => $userModule->pivot->can_manage_salary_payments ?? false,
                                                    ];
                                                }
                                                
                                                // Filter to only show enabled permissions
                                                $enabledPermissions = array_filter($permissions, function($value) {
                                                    return $value === true;
                                                });
                                            @endphp
                                            
                                            <div class="col-xl-6 col-lg-6 col-md-12 mb-4">
                                                <div class="card border">
                                                    <div class="card-header bg-light">
                                                        <div class="d-flex align-items-center">
                                                            <i class="bx bx-package me-2 text-primary"></i>
                                                            <h6 class="mb-0">{{ $module->name }}</h6>
                                                        </div>
                                                    </div>
                                                    <div class="card-body">
                                                        @if(count($enabledPermissions) > 0)
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
                                                        @else
                                                            <div class="text-center text-muted">
                                                                <i class="bx bx-info-circle me-2"></i>
                                                                <small>No specific permissions assigned</small>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- End::row-1 -->
</div>

@endsection

@section('styles')
<style>
    .module-card {
        cursor: pointer;
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }
    
    .module-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        border-color: #007bff;
    }
    
    .module-card:hover .avatar {
        transform: scale(1.1);
    }
    
    .avatar {
        transition: all 0.3s ease;
    }
</style>
@endsection

@section('scripts')
@endsection
