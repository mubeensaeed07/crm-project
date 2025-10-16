@extends('support::layouts.support-master')

@section('title') Users - SUPPORT @endsection

@section('content')
<div class="container-fluid">
    <!-- Start::row-1 -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">
                        Users Management
                    </div>
                    <div class="card-tools">
                        <a href="{{ route('support.users.create') }}" class="btn btn-primary">
                            <i class="bx bx-user-plus"></i> Create New User
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Statistics Cards -->
                    <div class="row mb-4">
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Users</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalUsers }}</div>
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
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Active Users</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $activeUsers }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-user-check fa-2x text-gray-300"></i>
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
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Inactive Users</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $inactiveUsers }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-user-times fa-2x text-gray-300"></i>
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
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">SUPPORT Users</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $users->where('modules', function($query) { $query->where('name', 'SUPPORT'); })->count() }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-headset fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Users Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Department</th>
                                    <th>Status</th>
                                    <th>Modules</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->full_name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->userInfo->phone ?? 'N/A' }}</td>
                                    <td>{{ $user->userInfo->department->name ?? 'N/A' }}</td>
                                    <td>
                                        @if($user->is_approved)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-warning">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($user->modules->count() > 0)
                                            @foreach($user->modules as $module)
                                                <span class="badge bg-info me-1">{{ $module->name }}</span>
                                            @endforeach
                                        @else
                                            <span class="text-muted">No modules assigned</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="#" class="btn btn-sm btn-info" title="View">
                                                <i class="bx bx-show"></i>
                                            </a>
                                            <a href="#" class="btn btn-sm btn-warning" title="Edit">
                                                <i class="bx bx-edit"></i>
                                            </a>
                                            <a href="#" class="btn btn-sm btn-danger" title="Delete" onclick="return confirm('Are you sure?')">
                                                <i class="bx bx-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">No users found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End::row-1 -->
</div>
@endsection
