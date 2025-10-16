@extends('layouts.master')

@section('title') Edit User - HRM @endsection

@section('content')
<div class="container-fluid">
    <!-- Start::row-1 -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">
                        <i class="bx bx-edit me-2"></i>
                        Edit User
                    </div>
                    <div class="card-tools">
                        <a href="{{ route('hrm.employees') }}" class="btn btn-secondary">
                            <i class="bx bx-arrow-back"></i> Back to Employees
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('hrm.users.update', $user->id) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                <div class="card custom-card">
                                    <div class="card-header">
                                        <div class="card-title">Basic Information</div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">First Name <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control @error('first_name') is-invalid @enderror" 
                                                       name="first_name" value="{{ old('first_name', $user->first_name) }}" required>
                                                @error('first_name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control @error('last_name') is-invalid @enderror" 
                                                       name="last_name" value="{{ old('last_name', $user->last_name) }}" required>
                                                @error('last_name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                                       name="email" value="{{ old('email', $user->email) }}" required>
                                                @error('email')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Phone</label>
                                                <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                                       name="phone" value="{{ old('phone', $user->phone) }}">
                                                @error('phone')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Salary</label>
                                                <input type="number" step="0.01" class="form-control @error('salary') is-invalid @enderror" 
                                                       name="salary" value="{{ old('salary', $user->userInfo->salary ?? '') }}">
                                                @error('salary')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                <div class="card custom-card">
                                    <div class="card-header">
                                        <div class="card-title">Additional Information</div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Gmail</label>
                                                <input type="email" class="form-control @error('gmail') is-invalid @enderror" 
                                                       name="gmail" value="{{ old('gmail', $user->userInfo->gmail ?? '') }}">
                                                @error('gmail')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">CNIC</label>
                                                <input type="text" class="form-control @error('cnic') is-invalid @enderror" 
                                                       name="cnic" value="{{ old('cnic', $user->userInfo->cnic ?? '') }}">
                                                @error('cnic')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Passport</label>
                                                <input type="text" class="form-control @error('passport') is-invalid @enderror" 
                                                       name="passport" value="{{ old('passport', $user->userInfo->passport ?? '') }}">
                                                @error('passport')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Date of Birth</label>
                                                <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" 
                                                       name="date_of_birth" value="{{ old('date_of_birth', $user->userInfo->date_of_birth ?? '') }}">
                                                @error('date_of_birth')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Gender</label>
                                                <select class="form-control @error('gender') is-invalid @enderror" name="gender">
                                                    <option value="">Select Gender</option>
                                                    <option value="male" {{ old('gender', $user->userInfo->gender ?? '') == 'male' ? 'selected' : '' }}>Male</option>
                                                    <option value="female" {{ old('gender', $user->userInfo->gender ?? '') == 'female' ? 'selected' : '' }}>Female</option>
                                                    <option value="other" {{ old('gender', $user->userInfo->gender ?? '') == 'other' ? 'selected' : '' }}>Other</option>
                                                </select>
                                                @error('gender')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Job Title</label>
                                                <input type="text" class="form-control @error('job_title') is-invalid @enderror" 
                                                       name="job_title" value="{{ old('job_title', $user->userInfo->job_title ?? '') }}">
                                                @error('job_title')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Department</label>
                                                <select class="form-control @error('department_id') is-invalid @enderror" name="department_id">
                                                    <option value="">Select Department</option>
                                                    @foreach($departments as $department)
                                                        <option value="{{ $department->id }}" {{ old('department_id', $user->userInfo->department_id ?? '') == $department->id ? 'selected' : '' }}>
                                                            {{ $department->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('department_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Company</label>
                                                <input type="text" class="form-control @error('company') is-invalid @enderror" 
                                                       name="company" value="{{ old('company', $user->userInfo->company ?? '') }}">
                                                @error('company')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mt-4">
                            <div class="col-xl-12">
                                <div class="card custom-card">
                                    <div class="card-header">
                                        <div class="card-title">Address Information</div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12 mb-3">
                                                <label class="form-label">Address</label>
                                                <textarea class="form-control @error('address') is-invalid @enderror" 
                                                          name="address" rows="3">{{ old('address', $user->userInfo->address ?? '') }}</textarea>
                                                @error('address')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">City</label>
                                                <input type="text" class="form-control @error('city') is-invalid @enderror" 
                                                       name="city" value="{{ old('city', $user->userInfo->city ?? '') }}">
                                                @error('city')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">State</label>
                                                <input type="text" class="form-control @error('state') is-invalid @enderror" 
                                                       name="state" value="{{ old('state', $user->userInfo->state ?? '') }}">
                                                @error('state')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">Country</label>
                                                <input type="text" class="form-control @error('country') is-invalid @enderror" 
                                                       name="country" value="{{ old('country', $user->userInfo->country ?? '') }}">
                                                @error('country')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Postal Code</label>
                                                <input type="text" class="form-control @error('postal_code') is-invalid @enderror" 
                                                       name="postal_code" value="{{ old('postal_code', $user->userInfo->postal_code ?? '') }}">
                                                @error('postal_code')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Debug Info -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="alert alert-warning">
                                    <h6>Debug Info for User ID: {{ $user->id }}</h6>
                                    <p><strong>User Modules Count:</strong> {{ $user->modules->count() }}</p>
                                    <p><strong>User Modules:</strong></p>
                                    <ul>
                                        @foreach($user->modules as $module)
                                        <li>
                                            <strong>{{ $module->name }}</strong> (ID: {{ $module->id }})
                                            @if($module->pivot)
                                                <br>Pivot Data: {{ json_encode($module->pivot->toArray()) }}
                                            @else
                                                <br><span class="text-danger">No Pivot Data!</span>
                                            @endif
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Module Assignment -->
                        <div class="row mt-4">
                            <div class="col-xl-6">
                                <div class="card custom-card">
                                    <div class="card-header">
                                        <div class="card-title">Module Assignment</div>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label class="form-label">Select Modules</label>
                                            <div class="row">
                                                @foreach($modules as $module)
                                                @php
                                                    $isAssigned = $user->modules->where('id', $module->id)->isNotEmpty();
                                                @endphp
                                                <div class="col-md-6 mb-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input module-checkbox" type="checkbox" name="modules[]" value="{{ $module->id }}" id="module_{{ $module->id }}" {{ $isAssigned ? 'checked' : '' }} onchange="togglePermissions({{ $module->id }})">
                                                        <label class="form-check-label" for="module_{{ $module->id }}">
                                                            {{ $module->name }}
                                                        </label>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                            @error('modules')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Permissions -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card custom-card">
                                    <div class="card-header">
                                        <div class="card-title">Permissions Configuration</div>
                                        <p class="text-muted mb-0">Select what permissions the user should have for each module</p>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            @foreach($modules as $module)
                                            @php
                                                $isAssigned = $user->modules->where('id', $module->id)->isNotEmpty();
                                                $userModule = $user->modules->where('id', $module->id)->first();
                                            @endphp
                                            <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                                                <div class="card custom-card module-permissions" id="permissions_{{ $module->id }}" style="display: {{ $isAssigned ? 'block' : 'none' }};">
                                                    <div class="card-header">
                                                        <div class="card-title">{{ $module->name }} Permissions</div>
                                                    </div>
                                                    <div class="card-body">
                                                        @if($module->name === 'HRM')
                                                        <!-- HRM module permissions -->
                                                        <div class="form-check mb-2">
                                                            <input class="form-check-input" type="checkbox" name="permissions[{{ $module->id }}][]" value="create_users" id="create_users_{{ $module->id }}" {{ $userModule && $userModule->pivot->can_create_users ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="create_users_{{ $module->id }}">
                                                                Create Users
                                                            </label>
                                                        </div>
                                                        <div class="form-check mb-2">
                                                            <input class="form-check-input" type="checkbox" name="permissions[{{ $module->id }}][]" value="edit_users" id="edit_users_{{ $module->id }}" {{ $userModule && $userModule->pivot->can_edit_users ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="edit_users_{{ $module->id }}">
                                                                Edit Users
                                                            </label>
                                                        </div>
                                                        <div class="form-check mb-2">
                                                            <input class="form-check-input" type="checkbox" name="permissions[{{ $module->id }}][]" value="delete_users" id="delete_users_{{ $module->id }}" {{ $userModule && $userModule->pivot->can_delete_users ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="delete_users_{{ $module->id }}">
                                                                Delete Users
                                                            </label>
                                                        </div>
                                                        <div class="form-check mb-2">
                                                            <input class="form-check-input" type="checkbox" name="permissions[{{ $module->id }}][]" value="reset_passwords" id="reset_passwords_{{ $module->id }}" {{ $userModule && $userModule->pivot->can_reset_passwords ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="reset_passwords_{{ $module->id }}">
                                                                Reset Passwords
                                                            </label>
                                                        </div>
                                                        <div class="form-check mb-2">
                                                            <input class="form-check-input" type="checkbox" name="permissions[{{ $module->id }}][]" value="assign_modules" id="assign_modules_{{ $module->id }}" {{ $userModule && $userModule->pivot->can_assign_modules ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="assign_modules_{{ $module->id }}">
                                                                Assign Modules
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="permissions[{{ $module->id }}][]" value="view_reports" id="view_reports_{{ $module->id }}" {{ $userModule && $userModule->pivot->can_view_reports ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="view_reports_{{ $module->id }}">
                                                                View Reports
                                                            </label>
                                                        </div>
                                                        @elseif($module->name === 'FINANCE')
                                                        <!-- Finance module permissions -->
                                                        <div class="form-check mb-2">
                                                            <input class="form-check-input" type="checkbox" name="permissions[{{ $module->id }}][]" value="view_reports" id="view_reports_{{ $module->id }}" {{ $userModule && $userModule->pivot->can_view_reports ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="view_reports_{{ $module->id }}">
                                                                Access Finance Module
                                                            </label>
                                                        </div>
                                                        <div class="form-check mb-2">
                                                            <input class="form-check-input" type="checkbox" name="permissions[{{ $module->id }}][]" value="mark_salary_paid" id="mark_salary_paid_{{ $module->id }}" {{ $userModule && $userModule->pivot->can_mark_salary_paid ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="mark_salary_paid_{{ $module->id }}">
                                                                Mark Salary Paid
                                                            </label>
                                                        </div>
                                                        <div class="form-check mb-2">
                                                            <input class="form-check-input" type="checkbox" name="permissions[{{ $module->id }}][]" value="mark_salary_pending" id="mark_salary_pending_{{ $module->id }}" {{ $userModule && $userModule->pivot->can_mark_salary_pending ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="mark_salary_pending_{{ $module->id }}">
                                                                Mark Salary Pending
                                                            </label>
                                                        </div>
                                                        <div class="form-check mb-2">
                                                            <input class="form-check-input" type="checkbox" name="permissions[{{ $module->id }}][]" value="view_salary_data" id="view_salary_data_{{ $module->id }}" {{ $userModule && $userModule->pivot->can_view_salary_data ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="view_salary_data_{{ $module->id }}">
                                                                View Salary Data
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="permissions[{{ $module->id }}][]" value="manage_salary_payments" id="manage_salary_payments_{{ $module->id }}" {{ $userModule && $userModule->pivot->can_manage_salary_payments ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="manage_salary_payments_{{ $module->id }}">
                                                                Manage Salary Payments
                                                            </label>
                                                        </div>
                                                        @elseif($module->name === 'SUPPORT')
                                                        <!-- Support module - no specific permissions needed -->
                                                        <div class="alert alert-info">
                                                            <i class="bx bx-info-circle"></i> SUPPORT module does not require specific permissions. All users with access can use all features.
                                                        </div>
                                                        @else
                                                        <!-- Default permissions for other modules -->
                                                        <div class="form-check mb-2">
                                                            <input class="form-check-input" type="checkbox" name="permissions[{{ $module->id }}][]" value="view_reports" id="view_reports_{{ $module->id }}" {{ $userModule && $userModule->pivot->can_view_reports ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="view_reports_{{ $module->id }}">
                                                                View Reports
                                                            </label>
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
                        
                        <div class="row mt-4">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">Update User</button>
                                <a href="{{ route('hrm.employees') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End::row-1 -->
</div>

<script>
function togglePermissions(moduleId) {
    const checkbox = document.getElementById('module_' + moduleId);
    const permissionsDiv = document.getElementById('permissions_' + moduleId);
    
    if (checkbox.checked) {
        permissionsDiv.style.display = 'block';
    } else {
        permissionsDiv.style.display = 'none';
        // Only uncheck permission checkboxes if the module checkbox is being unchecked by user action
        // Don't interfere with initial page load
        if (!checkbox.hasAttribute('data-initial-load')) {
            const permissionCheckboxes = permissionsDiv.querySelectorAll('input[type="checkbox"]');
            permissionCheckboxes.forEach(cb => cb.checked = false);
        }
    }
}

// Initialize permissions visibility on page load
document.addEventListener('DOMContentLoaded', function() {
    // Mark all module checkboxes as initial load to prevent interference
    document.querySelectorAll('.module-checkbox').forEach(checkbox => {
        checkbox.setAttribute('data-initial-load', 'true');
    });
    
    // Set up event listeners for future changes
    document.querySelectorAll('.module-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            this.removeAttribute('data-initial-load');
            togglePermissions(this.value);
        });
    });
});
</script>
@endsection
