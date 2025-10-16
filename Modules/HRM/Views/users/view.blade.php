@extends('layouts.master')

@section('title') View User - HRM @endsection

@section('content')
<div class="container-fluid">
    <!-- Start::row-1 -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">
                        <i class="bx bx-user me-2"></i>
                        User Details
                    </div>
                    <div class="card-tools">
                        <a href="{{ route('hrm.employees') }}" class="btn btn-secondary">
                            <i class="bx bx-arrow-back"></i> Back to Employees
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 mb-4">
                            <div class="card custom-card">
                                <div class="card-body text-center">
                                    <div class="mb-3">
                                        @if($user->userInfo && $user->userInfo->avatar)
                                            <img src="{{ asset('storage/' . $user->userInfo->avatar) }}" alt="Avatar" class="avatar avatar-xl rounded-circle">
                                        @else
                                            <span class="avatar avatar-xl bg-primary-transparent rounded-circle">
                                                <i class="bx bx-user fs-24"></i>
                                            </span>
                                        @endif
                                    </div>
                                    <h5 class="card-title">{{ $user->first_name }} {{ $user->last_name }}</h5>
                                    <p class="text-muted">{{ $user->email }}</p>
                                    <div class="mt-3">
                                        @if($user->is_approved)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-warning">Pending</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-xl-8 col-lg-6 col-md-6 col-sm-12">
                            <div class="card custom-card">
                                <div class="card-header">
                                    <div class="card-title">Personal Information</div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-semibold">Employee ID</label>
                                            <p class="text-muted">EMP{{ str_pad($user->id, 3, '0', STR_PAD_LEFT) }}</p>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-semibold">Full Name</label>
                                            <p class="text-muted">{{ $user->first_name }} {{ $user->last_name }}</p>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-semibold">Email</label>
                                            <p class="text-muted">{{ $user->email }}</p>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-semibold">Phone</label>
                                            <p class="text-muted">{{ $user->phone ?? 'Not provided' }}</p>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-semibold">Salary</label>
                                            <p class="text-muted">${{ number_format($user->userInfo->salary ?? 0, 2) }}</p>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-semibold">Status</label>
                                            <p class="text-muted">
                                                @if($user->is_approved)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-warning">Pending</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    @if($user->userInfo)
                    <div class="row mt-4">
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <div class="card custom-card">
                                <div class="card-header">
                                    <div class="card-title">Additional Information</div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-semibold">Gmail</label>
                                            <p class="text-muted">{{ $user->userInfo->gmail ?? 'Not provided' }}</p>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-semibold">CNIC</label>
                                            <p class="text-muted">{{ $user->userInfo->cnic ?? 'Not provided' }}</p>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-semibold">Passport</label>
                                            <p class="text-muted">{{ $user->userInfo->passport ?? 'Not provided' }}</p>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-semibold">Date of Birth</label>
                                            <p class="text-muted">{{ $user->userInfo->date_of_birth ? \Carbon\Carbon::parse($user->userInfo->date_of_birth)->format('M d, Y') : 'Not provided' }}</p>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-semibold">Gender</label>
                                            <p class="text-muted">{{ ucfirst($user->userInfo->gender ?? 'Not specified') }}</p>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-semibold">Job Title</label>
                                            <p class="text-muted">{{ $user->userInfo->job_title ?? 'Not specified' }}</p>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-semibold">Department</label>
                                            <p class="text-muted">{{ $user->userInfo->department->name ?? 'Not assigned' }}</p>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-semibold">Company</label>
                                            <p class="text-muted">{{ $user->userInfo->company ?? 'Not specified' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <div class="card custom-card">
                                <div class="card-header">
                                    <div class="card-title">Address Information</div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12 mb-3">
                                            <label class="form-label fw-semibold">Address</label>
                                            <p class="text-muted">{{ $user->userInfo->address ?? 'Not provided' }}</p>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-semibold">City</label>
                                            <p class="text-muted">{{ $user->userInfo->city ?? 'Not provided' }}</p>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-semibold">State</label>
                                            <p class="text-muted">{{ $user->userInfo->state ?? 'Not provided' }}</p>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-semibold">Country</label>
                                            <p class="text-muted">{{ $user->userInfo->country ?? 'Not provided' }}</p>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-semibold">Postal Code</label>
                                            <p class="text-muted">{{ $user->userInfo->postal_code ?? 'Not provided' }}</p>
                                        </div>
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
