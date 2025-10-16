@extends('layouts.master')

@section('title') Payroll - HRM @endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">Payroll Management</div>
                    <div class="card-tools">
                        <a href="{{ route('hrm.dashboard') }}" class="btn btn-secondary">
                            <i class="bx bx-arrow-back"></i> Back to HRM Dashboard
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                            <div class="card custom-card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <span class="avatar avatar-md bg-primary-transparent">
                                                <i class="bx bx-money fs-18"></i>
                                            </span>
                                        </div>
                                        <div>
                                            <p class="mb-0 text-muted">Total Payroll</p>
                                            <h4 class="mb-0 fw-semibold">${{ number_format($users->whereNotNull('salary')->sum('salary'), 2) }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                            <div class="card custom-card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <span class="avatar avatar-md bg-success-transparent">
                                                <i class="bx bx-user fs-18"></i>
                                            </span>
                                        </div>
                                        <div>
                                            <p class="mb-0 text-muted">Employees with Salary</p>
                                            <h4 class="mb-0 fw-semibold">{{ $users->whereNotNull('salary')->count() }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                            <div class="card custom-card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <span class="avatar avatar-md bg-warning-transparent">
                                                <i class="bx bx-calculator fs-18"></i>
                                            </span>
                                        </div>
                                        <div>
                                            <p class="mb-0 text-muted">Average Salary</p>
                                            <h4 class="mb-0 fw-semibold">${{ number_format($users->whereNotNull('salary')->avg('salary') ?? 0, 2) }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                            <div class="card custom-card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <span class="avatar avatar-md bg-info-transparent">
                                                <i class="bx bx-calendar fs-18"></i>
                                            </span>
                                        </div>
                                        <div>
                                            <p class="mb-0 text-muted">Pay Period</p>
                                            <h4 class="mb-0 fw-semibold">{{ date('M Y') }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-12">
                            <div class="card custom-card">
                                <div class="card-header">
                                    <div class="card-title">Employee Payroll</div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Employee ID</th>
                                                    <th>Name</th>
                                                    <th>Department</th>
                                                    <th>Position</th>
                                                    <th>Salary</th>
                                                    <th>Status</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($users as $user)
                                                <tr>
                                                    <td>EMP{{ str_pad($user->id, 3, '0', STR_PAD_LEFT) }}</td>
                                                    <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                                                    <td>{{ $user->userInfo->department->name ?? 'Not Assigned' }}</td>
                                                    <td>{{ $user->userInfo->job_title ?? 'Not Specified' }}</td>
                                                    <td>
                                                        @if($user->userInfo && $user->userInfo->salary)
                                                            <span class="badge bg-success">${{ number_format($user->userInfo->salary, 2) }}</span>
                                                        @else
                                                            <span class="badge bg-warning">Not Set</span>
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
                                                        <button class="btn btn-sm btn-primary">View Details</button>
                                                        <button class="btn btn-sm btn-warning">Edit Salary</button>
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="7" class="text-center">No employees found</td>
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
            </div>
        </div>
    </div>
</div>
@endsection
