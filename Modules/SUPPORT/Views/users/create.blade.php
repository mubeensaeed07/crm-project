@extends('support::layouts.support-master')

@section('title') Create User - SUPPORT @endsection

@section('content')
<div class="container-fluid">
    <!-- Start::row-1 -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">
                        Create SUPPORT User
                    </div>
                    <div class="card-tools">
                        <a href="{{ route('support.dashboard') }}" class="btn btn-secondary">
                            <i class="bx bx-arrow-back"></i> Back to SUPPORT Dashboard
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
                    
                    <form method="POST" action="{{ route('support.users.store') }}">
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
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Email <span class="text-danger">*</span></label>
                                                    <input type="email" class="form-control" name="email" value="{{ old('email') }}" required>
                                                    @error('email')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Phone</label>
                                                    <input type="text" class="form-control" name="phone" value="{{ old('phone') }}">
                                                    @error('phone')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Additional Information -->
                            <div class="col-xl-6">
                                <div class="card custom-card">
                                    <div class="card-header">
                                        <div class="card-title">Additional Information</div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Gmail</label>
                                                    <input type="email" class="form-control" name="gmail" value="{{ old('gmail') }}">
                                                    @error('gmail')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">CNIC</label>
                                                    <input type="text" class="form-control" name="cnic" value="{{ old('cnic') }}">
                                                    @error('cnic')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Passport</label>
                                                    <input type="text" class="form-control" name="passport" value="{{ old('passport') }}">
                                                    @error('passport')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Date of Birth</label>
                                                    <input type="date" class="form-control" name="date_of_birth" value="{{ old('date_of_birth') }}">
                                                    @error('date_of_birth')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
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
                                                    @error('gender')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Salary</label>
                                                    <input type="number" class="form-control" name="salary" value="{{ old('salary') }}" min="0" step="0.01">
                                                    @error('salary')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Address Information -->
                            <div class="col-xl-6">
                                <div class="card custom-card">
                                    <div class="card-header">
                                        <div class="card-title">Address Information</div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="mb-3">
                                                    <label class="form-label">Address</label>
                                                    <textarea class="form-control" name="address" rows="3">{{ old('address') }}</textarea>
                                                    @error('address')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="form-label">City</label>
                                                    <input type="text" class="form-control" name="city" value="{{ old('city') }}">
                                                    @error('city')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="form-label">State</label>
                                                    <input type="text" class="form-control" name="state" value="{{ old('state') }}">
                                                    @error('state')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="form-label">Country</label>
                                                    <input type="text" class="form-control" name="country" value="{{ old('country') }}">
                                                    @error('country')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Postal Code</label>
                                                    <input type="text" class="form-control" name="postal_code" value="{{ old('postal_code') }}">
                                                    @error('postal_code')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Job Information -->
                            <div class="col-xl-6">
                                <div class="card custom-card">
                                    <div class="card-header">
                                        <div class="card-title">Job Information</div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Job Title</label>
                                                    <input type="text" class="form-control" name="job_title" value="{{ old('job_title') }}">
                                                    @error('job_title')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Department</label>
                                                    <select class="form-control" name="department_id">
                                                        <option value="">Select Department</option>
                                                        @foreach($departments as $department)
                                                            <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                                                {{ $department->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('department_id')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Company</label>
                                                    <input type="text" class="form-control" name="company" value="{{ old('company') }}">
                                                    @error('company')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">User Type</label>
                                                    <input type="text" class="form-control" value="User" readonly>
                                                    <input type="hidden" name="user_type_id" value="3">
                                                    <small class="text-muted">SUPPORT module only creates regular users</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Module Assignment -->
                            <div class="col-12">
                                <div class="card custom-card">
                                    <div class="card-header">
                                        <div class="card-title">Module Assignment</div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
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
                                                                       data-module-id="{{ $module->id }}"
                                                                       checked disabled>
                                                                <label class="form-check-label" for="module_{{ $module->id }}">
                                                                    {{ $module->name }} <span class="text-success">(Pre-selected)</span>
                                                                </label>
                                                                <!-- Hidden input to ensure the value is submitted -->
                                                                <input type="hidden" name="modules[]" value="{{ $module->id }}">
                                                            </div>
                                                        </div>
                                                        @endforeach
                                                    </div>
                                                    @error('modules')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <!-- SUPPORT Module Info -->
                                                <div class="alert alert-success">
                                                    <i class="bx bx-check-circle me-2"></i>
                                                    <strong>Creating SUPPORT User:</strong> This user will be created specifically for the SUPPORT module with full access to all SUPPORT features (User Support, Dealer Support, etc.).
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="col-12">
                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bx bx-user-plus me-2"></i>Create User
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
    // SUPPORT module doesn't need permission management
    // All users get full access to SUPPORT features
    console.log('SUPPORT module: Permission-free access enabled');
});
</script>
@endsection
