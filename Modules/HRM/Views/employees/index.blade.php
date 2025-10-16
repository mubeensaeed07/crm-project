@extends('layouts.master')

@section('title') Employees - HRM @endsection

@section('content')
<div class="container-fluid">
    <!-- Start::row-1 -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">
                        Employee Management
                    </div>
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
                                                <i class="bx bx-user fs-18"></i>
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
                                                <i class="bx bx-user-x fs-18"></i>
                                            </span>
                                        </div>
                                        <div>
                                            <p class="mb-0 text-muted">On Leave</p>
                                            <h4 class="mb-0 fw-semibold">{{ $users->where('is_approved', false)->count() }}</h4>
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
                                                <i class="bx bx-group fs-18"></i>
                                            </span>
                                        </div>
                                        <div>
                                            <p class="mb-0 text-muted">Departments</p>
                                            <h4 class="mb-0 fw-semibold">{{ \App\Models\Department::where('is_active', true)->count() }}</h4>
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
                                    <div class="card-title">Employee List</div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Employee ID</th>
                                                    <th>Name</th>
                                                    <th>Email</th>
                                                    <th>Department</th>
                                                    <th>Position</th>
                                                    <th>Status</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($users as $user)
                                                <tr>
                                                    <td>EMP{{ str_pad($user->id, 3, '0', STR_PAD_LEFT) }}</td>
                                                    <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                                                    <td>{{ $user->email }}</td>
                                                    <td>{{ $user->userInfo->department->name ?? 'Not Assigned' }}</td>
                                                    <td>{{ $user->userInfo->job_title ?? 'Not Specified' }}</td>
                                                    <td>
                                                        @if($user->is_approved)
                                                            <span class="badge bg-success">Active</span>
                                                        @else
                                                            <span class="badge bg-warning">Pending</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @php
                                                            $auth = \Modules\HRM\Http\Controllers\HRMController::class;
                                                            $currentUser = Auth::guard('supervisor')->check() ? Auth::guard('supervisor')->user() : Auth::user();
                                                            $isSupervisor = Auth::guard('supervisor')->check();
                                                            $canView = $isSupervisor ? $currentUser->hasPermission(1, 'can_view_reports') : true;
                                                            $canEdit = $isSupervisor ? $currentUser->hasPermission(1, 'can_edit_users') : true;
                                                            $canDelete = $isSupervisor ? $currentUser->hasPermission(1, 'can_delete_users') : true;
                                                        @endphp
                                                        
                                                        @if($canView)
                                                            <a href="{{ route('hrm.users.view', $user->id) }}" class="btn btn-sm btn-primary">View</a>
                                                        @else
                                                            <button class="btn btn-sm btn-primary" disabled title="Permission Required">View</button>
                                                        @endif
                                                        
                                                        @if($canEdit)
                                                            <a href="{{ route('hrm.users.edit', $user->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                                        @else
                                                            <button class="btn btn-sm btn-warning" disabled title="Permission Required">Edit</button>
                                                        @endif
                                                        
                                                        @if($canDelete)
                                                            <form method="POST" action="{{ route('hrm.users.delete', $user->id) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this user?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                                            </form>
                                                        @else
                                                            <button class="btn btn-sm btn-danger" disabled title="Permission Required">Delete</button>
                                                        @endif
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
    <!-- End::row-1 -->
</div>
@endsection
