@extends('layouts.master')

@section('title') Create User - HRM @endsection

@section('content')
<div class="container-fluid">
    <!-- Start::row-1 -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">
                        Create New User
                    </div>
                    <div class="card-tools">
                        <a href="{{ route('hrm.dashboard') }}" class="btn btn-secondary">
                            <i class="bx bx-arrow-back"></i> Back to HRM Dashboard
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <h6>Please fix the following errors:</h6>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <form method="POST" action="{{ route('hrm.users.store') }}">
                        @csrf
                        <div class="row">
                            <!-- Basic Information -->
                            <div class="col-xl-6">
                                <div class="card custom-card">
                                    <div class="card-header">
                                        <div class="card-title">Basic Information</div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">First Name <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="first_name" value="{{ old('first_name') }}" required>
                                                    @error('first_name')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="last_name" value="{{ old('last_name') }}" required>
                                                    @error('last_name')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="mb-3">
                                                    <label class="form-label">Email Address <span class="text-danger">*</span></label>
                                                    <input type="email" class="form-control" name="email" value="{{ old('email') }}" required>
                                                    @error('email')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Salary</label>
                                                    <input type="number" class="form-control" name="salary" value="{{ old('salary') }}" step="0.01" min="0">
                                                    @error('salary')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Phone Number</label>
                                                    <input type="tel" class="form-control" name="phone" value="{{ old('phone') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Gmail</label>
                                                    <input type="email" class="form-control" name="gmail" value="{{ old('gmail') }}" placeholder="Enter Gmail address">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">CNIC</label>
                                                    <input type="text" class="form-control" name="cnic" value="{{ old('cnic') }}" placeholder="Enter CNIC number">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Passport</label>
                                                    <input type="text" class="form-control" name="passport" value="{{ old('passport') }}" placeholder="Enter passport number">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Date of Birth</label>
                                                    <input type="date" class="form-control" name="date_of_birth" value="{{ old('date_of_birth') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Gender</label>
                                                    <select class="form-control" name="gender">
                                                        <option value="">Select Gender</option>
                                                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                                        <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="mb-3">
                                                    <label class="form-label">Address</label>
                                                    <textarea class="form-control" name="address" rows="3" placeholder="Enter full address">{{ old('address') }}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="form-label">City</label>
                                                    <input type="text" class="form-control" name="city" value="{{ old('city') }}" placeholder="Enter city">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="form-label">State</label>
                                                    <input type="text" class="form-control" name="state" value="{{ old('state') }}" placeholder="Enter state">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="form-label">Country</label>
                                                    <input type="text" class="form-control" name="country" value="{{ old('country') }}" placeholder="Enter country">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Postal Code</label>
                                                    <input type="text" class="form-control" name="postal_code" value="{{ old('postal_code') }}" placeholder="Enter postal code">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Job Title</label>
                                                    <input type="text" class="form-control" name="job_title" value="{{ old('job_title') }}" placeholder="Enter job title">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Department</label>
                                                    <select class="form-control" name="department_id">
                                                        <option value="">Select Department</option>
                                                        @foreach($departments as $department)
                                                            <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>{{ $department->name }} ({{ $department->code }})</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Company</label>
                                                    <input type="text" class="form-control" name="company" value="{{ old('company') }}" placeholder="Enter company">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- User Role & Permissions -->
                            <div class="col-xl-6">
                                <div class="card custom-card">
                                    <div class="card-header">
                                        <div class="card-title">Role & Permissions</div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">User Role <span class="text-danger">*</span></label>
                                                    <select class="form-control" name="role_id" required>
                                                        <option value="">Select Role</option>
                                                        @foreach($availableRoles as $role)
                                                        <option value="{{ $role['id'] }}" {{ old('role_id') == $role['id'] ? 'selected' : '' }}>
                                                            {{ $role['name'] }} - {{ $role['description'] }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                    @error('role_id')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="mb-3">
                                                    <label class="form-label">Module Access <span class="text-danger">*</span></label>
                                                    <div class="row">
                                                        @foreach($modules as $module)
                                                        <div class="col-md-6">
                                                            <div class="form-check mb-2">
                                                                <input class="form-check-input module-checkbox" type="checkbox" 
                                                                       name="modules[]" value="{{ $module->id }}" 
                                                                       id="module_{{ $module->id }}"
                                                                       data-module-id="{{ $module->id }}">
                                                                <label class="form-check-label" for="module_{{ $module->id }}">
                                                                    {{ $module->name }}
                                                                </label>
                                                            </div>
                                                        </div>
                                                        @endforeach
                                                    </div>
                                                    @error('modules')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                
                                                <!-- Permissions Section -->
                                                <div id="permissions-section" style="display: none;">
                                                    <label class="form-label">Permissions</label>
                                                    <div id="permissions-container">
                                                        <!-- Permissions will be dynamically added here -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="col-12">
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bx bx-save"></i> Create User
                                    </button>
                                </div>
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
document.addEventListener('DOMContentLoaded', function() {
    const moduleCheckboxes = document.querySelectorAll('.module-checkbox');
    const permissionsSection = document.getElementById('permissions-section');
    const permissionsContainer = document.getElementById('permissions-container');
    
    moduleCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updatePermissionsSection();
        });
    });
    
    function updatePermissionsSection() {
        const checkedModules = Array.from(moduleCheckboxes).filter(cb => cb.checked);
        
        if (checkedModules.length > 0) {
            permissionsSection.style.display = 'block';
            renderPermissions(checkedModules);
        } else {
            permissionsSection.style.display = 'none';
        }
    }
    
    function renderPermissions(modules) {
        permissionsContainer.innerHTML = '';
        
        modules.forEach(module => {
            const moduleId = module.dataset.moduleId;
            const moduleName = module.nextElementSibling.textContent.trim();
            
            const modulePermissionsDiv = document.createElement('div');
            modulePermissionsDiv.className = 'mb-3';
            
            // Check module type
            const isFinanceModule = moduleName.toUpperCase().includes('FINANCE');
            const isHRMModule = moduleName.toUpperCase().includes('HRM');
            const isSupportModule = moduleName.toUpperCase().includes('SUPPORT');
            
            let permissionsHTML = '';
            
            if (isFinanceModule) {
                // Finance module - only show Finance-specific permissions
                permissionsHTML = `
                    <h6 class="text-primary">${moduleName}</h6>
                    <div class="alert alert-info">
                        <i class="bx bx-info-circle"></i> Finance module - only show Finance-specific permissions
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" 
                                       name="permissions[${moduleId}][]" 
                                       value="view_reports" 
                                       id="permission_${moduleId}_view">
                                <label class="form-check-label" for="permission_${moduleId}_view">
                                    Access Finance Module
                                </label>
                            </div>
                        </div>
                    </div>
                    <hr class="my-3">
                    <h6 class="text-success">Finance Permissions</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" 
                                       name="permissions[${moduleId}][]" 
                                       value="mark_salary_paid" 
                                       id="permission_${moduleId}_mark_paid">
                                <label class="form-check-label" for="permission_${moduleId}_mark_paid">
                                    Mark Salary Paid
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" 
                                       name="permissions[${moduleId}][]" 
                                       value="mark_salary_pending" 
                                       id="permission_${moduleId}_mark_pending">
                                <label class="form-check-label" for="permission_${moduleId}_mark_pending">
                                    Mark Salary Pending
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" 
                                       name="permissions[${moduleId}][]" 
                                       value="view_salary_data" 
                                       id="permission_${moduleId}_view_salary">
                                <label class="form-check-label" for="permission_${moduleId}_view_salary">
                                    View Salary Data
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" 
                                       name="permissions[${moduleId}][]" 
                                       value="manage_salary_payments" 
                                       id="permission_${moduleId}_manage_payments">
                                <label class="form-check-label" for="permission_${moduleId}_manage_payments">
                                    Manage Salary Payments
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" 
                                       name="permissions[${moduleId}][]" 
                                       value="view_reports" 
                                       id="permission_${moduleId}_view_reports">
                                <label class="form-check-label" for="permission_${moduleId}_view_reports">
                                    View Reports
                                </label>
                            </div>
                        </div>
                    </div>
                `;
            } else if (isHRMModule) {
                // HRM module - show generic user management permissions
                permissionsHTML = `
                    <h6 class="text-primary">${moduleName}</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" 
                                       name="permissions[${moduleId}][]" 
                                       value="view_reports" 
                                       id="permission_${moduleId}_view">
                                <label class="form-check-label" for="permission_${moduleId}_view">
                                    View Reports
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" 
                                       name="permissions[${moduleId}][]" 
                                       value="create_users" 
                                       id="permission_${moduleId}_create">
                                <label class="form-check-label" for="permission_${moduleId}_create">
                                    Create Users
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" 
                                       name="permissions[${moduleId}][]" 
                                       value="edit_users" 
                                       id="permission_${moduleId}_edit">
                                <label class="form-check-label" for="permission_${moduleId}_edit">
                                    Edit Users
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" 
                                       name="permissions[${moduleId}][]" 
                                       value="delete_users" 
                                       id="permission_${moduleId}_delete">
                                <label class="form-check-label" for="permission_${moduleId}_delete">
                                    Delete Users
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" 
                                       name="permissions[${moduleId}][]" 
                                       value="reset_passwords" 
                                       id="permission_${moduleId}_reset">
                                <label class="form-check-label" for="permission_${moduleId}_reset">
                                    Reset Passwords
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" 
                                       name="permissions[${moduleId}][]" 
                                       value="assign_modules" 
                                       id="permission_${moduleId}_assign">
                                <label class="form-check-label" for="permission_${moduleId}_assign">
                                    Assign Modules
                                </label>
                            </div>
                        </div>
                    </div>
                `;
            } else if (isSupportModule) {
                // Support module - no specific permissions needed
                permissionsHTML = `
                    <h6 class="text-primary">${moduleName}</h6>
                    <div class="alert alert-info">
                        <i class="bx bx-info-circle"></i> SUPPORT module does not require specific permissions. All users with access can use all features.
                    </div>
                `;
            } else {
                // Default for other modules
                permissionsHTML = `
                    <h6 class="text-primary">${moduleName}</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" 
                                       name="permissions[${moduleId}][]" 
                                       value="view_reports" 
                                       id="permission_${moduleId}_view">
                                <label class="form-check-label" for="permission_${moduleId}_view">
                                    View Reports
                                </label>
                            </div>
                        </div>
                    </div>
                `;
            }
            
            modulePermissionsDiv.innerHTML = permissionsHTML;
            permissionsContainer.appendChild(modulePermissionsDiv);
        });
    }
});
</script>
@endsection
