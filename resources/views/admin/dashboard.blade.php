@extends('layouts.master')

@section('title') Admin Dashboard @endsection

@section('content')
<div class="container-fluid">
    <!-- Start::row-1 -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">
                        Admin Dashboard
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <h4>Welcome, {{ $user->full_name }}!</h4>
                            @php
                                $userType = $user->userInfo && $user->userInfo->userType ? $user->userInfo->userType : null;
                            @endphp
                            @if($userType)
                                <p class="text-muted">
                                    <span class="badge bg-info me-2">{{ $userType->name }}</span>
                                    Choose a module to manage your system
                                </p>
                            @else
                                <p class="text-muted">Choose a module to manage your system</p>
                            @endif
                        </div>
                    </div>
                    
                    
                    <!-- User Type Information Card -->
                    @if($userType)
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
                                            <h5 class="mb-1">Your Role: {{ $userType->name }}</h5>
                                            <p class="text-muted mb-0">{{ $userType->description ?? 'You have been assigned this role by your administrator.' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Module Cards -->
                    <div class="row">
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-4">
                            <div class="card custom-card h-100 module-card" onclick="window.location.href='/hrm'">
                                <div class="card-body text-center">
                                    <div class="mb-3">
                                        <span class="avatar avatar-xl bg-primary-transparent">
                                            <i class="bx bx-group fs-24"></i>
                                        </span>
                                    </div>
                                    <h5 class="card-title">HRM</h5>
                                    <p class="text-muted">Human Resource Management System</p>
                                    <div class="mt-3">
                                        <span class="badge bg-success">Active</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-4">
                            <div class="card custom-card h-100 module-card" onclick="window.location.href='/finance'">
                                <div class="card-body text-center">
                                    <div class="mb-3">
                                        <span class="avatar avatar-xl bg-success-transparent">
                                            <i class="bx bx-money fs-24"></i>
                                        </span>
                                    </div>
                                    <h5 class="card-title">FINANCE</h5>
                                    <p class="text-muted">Financial Management System</p>
                                    <div class="mt-3">
                                        <span class="badge bg-success">Active</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-4">
                            <div class="card custom-card h-100 module-card" onclick="window.location.href='/support'">
                                <div class="card-body text-center">
                                    <div class="mb-3">
                                        <span class="avatar avatar-xl bg-info-transparent">
                                            <i class="bx bx-support fs-24"></i>
                                        </span>
                                    </div>
                                    <h5 class="card-title">SUPPORT</h5>
                                    <p class="text-muted">Customer Support Management</p>
                                    <div class="mt-3">
                                        <span class="badge bg-success">Active</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-4">
                            <div class="card custom-card h-100 module-card" onclick="window.location.href='/reports'">
                                <div class="card-body text-center">
                                    <div class="mb-3">
                                        <span class="avatar avatar-xl bg-warning-transparent">
                                            <i class="bx bx-bar-chart-alt-2 fs-24"></i>
                                        </span>
                                    </div>
                                    <h5 class="card-title">Reports & Analytics</h5>
                                    <p class="text-muted">View reports and analytics dashboard</p>
                                    <div class="mt-3">
                                        <span class="badge bg-success">Active</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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