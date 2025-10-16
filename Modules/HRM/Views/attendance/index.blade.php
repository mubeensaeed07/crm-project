@extends('layouts.master')

@section('title') Attendance - HRM @endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">Attendance Management</div>
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
                                                <i class="bx bx-time fs-18"></i>
                                            </span>
                                        </div>
                                        <div>
                                            <p class="mb-0 text-muted">Total Employees</p>
                                            <h4 class="mb-0 fw-semibold">{{ $users->count() }}</h4>
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
                                                <i class="bx bx-user-check fs-18"></i>
                                            </span>
                                        </div>
                                        <div>
                                            <p class="mb-0 text-muted">Active Employees</p>
                                            <h4 class="mb-0 fw-semibold">{{ $users->where('is_approved', true)->count() }}</h4>
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
                                                <i class="bx bx-calendar fs-18"></i>
                                            </span>
                                        </div>
                                        <div>
                                            <p class="mb-0 text-muted">Today's Date</p>
                                            <h4 class="mb-0 fw-semibold">{{ date('M d, Y') }}</h4>
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
                                                <i class="bx bx-clock fs-18"></i>
                                            </span>
                                        </div>
                                        <div>
                                            <p class="mb-0 text-muted">Current Time</p>
                                            <h4 class="mb-0 fw-semibold">{{ date('H:i A') }}</h4>
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
                                    <div class="card-title">Employee Attendance</div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Employee ID</th>
                                                    <th>Name</th>
                                                    <th>Department</th>
                                                    <th>Check In</th>
                                                    <th>Check Out</th>
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
                                                    <td>
                                                        <span class="badge bg-success">09:00 AM</span>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-warning">Not Checked Out</span>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-success">Present</span>
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-sm btn-primary">Mark Attendance</button>
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
