@extends('layouts.master')

@section('title') Users Management - HRM @endsection

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
                        @if(hasPermission(1, 'can_create_users'))
                            <a href="{{ route('hrm.users.create') }}" class="btn btn-primary">
                                <i class="bx bx-plus"></i> Create New User
                            </a>
                        @else
                            <button class="btn btn-primary" onclick="showPermissionError('create users')">
                                <i class="bx bx-plus"></i> Create New User
                            </button>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <!-- Statistics Cards -->
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
                                            <p class="mb-0 text-muted">Total Users</p>
                                            <h4 class="mb-0 fw-semibold">{{ $totalUsers }}</h4>
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
                                            <p class="mb-0 text-muted">Active Users</p>
                                            <h4 class="mb-0 fw-semibold">{{ $activeUsers }}</h4>
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
                                            <p class="mb-0 text-muted">Inactive Users</p>
                                            <h4 class="mb-0 fw-semibold">{{ $inactiveUsers }}</h4>
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
                                            <h4 class="mb-0 fw-semibold">{{ $departments }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Users List -->
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="card custom-card">
                                <div class="card-header">
                                    <div class="card-title">All Users</div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Email</th>
                                                    <th>Department</th>
                                                    <th>Role</th>
                                                    <th>Status</th>
                                                    <th>Created By</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($users as $user)
                                                    <tr>
                                                        <td>{{ $user->full_name ?? $user->name }}</td>
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
                                                            @if($user->role_id == 1)
                                                                <span class="badge bg-danger">SuperAdmin</span>
                                                            @elseif($user->role_id == 2)
                                                                <span class="badge bg-info">Admin</span>
                                                            @elseif($user->role_id == 3)
                                                                <span class="badge bg-primary">User</span>
                                                            @elseif($user->role_id == 7)
                                                                <span class="badge bg-warning">Supervisor</span>
                                                            @else
                                                                <span class="badge bg-secondary">Unknown</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($user->is_approved)
                                                                <span class="badge bg-success">Active</span>
                                                            @else
                                                                <span class="badge bg-warning">Inactive</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @php
                                                                $creator = null;
                                                                if ($user->userInfo && $user->userInfo->created_by_id) {
                                                                    if ($user->userInfo->created_by_type == 'supervisor') {
                                                                        // Look in supervisors table
                                                                        $supervisor = \App\Models\Supervisor::find($user->userInfo->created_by_id);
                                                                        if ($supervisor) {
                                                                            $creator = $supervisor;
                                                                        }
                                                                    } elseif ($user->userInfo->created_by_type == 'admin') {
                                                                        // Look in users table
                                                                        $admin = \App\Models\User::find($user->userInfo->created_by_id);
                                                                        if ($admin) {
                                                                            $creator = $admin;
                                                                        }
                                                                    }
                                                                }
                                                                
                                                                // Fallback to admin if createdBy doesn't exist
                                                                if (!$creator) {
                                                                    $admin = \App\Models\User::find($user->admin_id);
                                                                    if ($admin) {
                                                                        $creator = $admin;
                                                                    }
                                                                }
                                                            @endphp
                                                            @if($creator)
                                                                <span class="badge bg-info">{{ $creator->first_name }} {{ $creator->last_name }}</span>
                                                            @else
                                                                <span class="badge bg-secondary">Unknown</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if(hasPermission(1, 'can_edit_users'))
                                                                <a href="{{ route('hrm.users.edit', $user->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                                            @else
                                                                <button class="btn btn-sm btn-warning" onclick="showPermissionError('edit users')">Edit</button>
                                                            @endif
                                                            @if(hasPermission(1, 'can_delete_users'))
                                                                <button class="btn btn-sm btn-danger" onclick="confirmDelete({{ $user->id }})">Delete</button>
                                                            @else
                                                                <button class="btn btn-sm btn-danger" onclick="showPermissionError('delete users')">Delete</button>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="7" class="text-center">No users found</td>
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

<!-- Permission Error Modal -->
<div class="modal fade" id="permissionErrorModal" tabindex="-1" aria-labelledby="permissionErrorModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="permissionErrorModalLabel">
                    <i class="bx bx-shield-x me-2"></i>Permission Required
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <i class="bx bx-shield-x text-danger" style="font-size: 3rem;"></i>
                    <h4 class="mt-3 text-danger">Access Denied</h4>
                    <p class="text-muted">You do not have permission to <span id="permissionAction"></span>.</p>
                    <p class="text-muted">Please contact your administrator to request access.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
function showPermissionError(action) {
    document.getElementById('permissionAction').textContent = action;
    var modal = new bootstrap.Modal(document.getElementById('permissionErrorModal'));
    modal.show();
}

function confirmDelete(userId) {
    if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
        // Create a form and submit it
        var form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("hrm.users.delete", ":id") }}'.replace(':id', userId);
        
        // Add CSRF token
        var csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        // Add method override for DELETE
        var methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        form.appendChild(methodField);
        
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection
