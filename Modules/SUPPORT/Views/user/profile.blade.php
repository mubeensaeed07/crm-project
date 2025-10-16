@extends('support::layouts.support-master')

@section('title') User Profile - SUPPORT @endsection

@section('content')
<div class="container-fluid">
    <!-- Start::row-1 -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">
                        User Profile
                    </div>
                    <div class="card-tools">
                        <a href="{{ route('support.user') }}" class="btn btn-secondary">
                            <i class="bx bx-arrow-back"></i> Back to User Support
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- User Information -->
                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-6">
                            <div class="card custom-card">
                                <div class="card-body text-center">
                                    <div class="mb-3">
                                        <span class="avatar avatar-xxl bg-primary-transparent">
                                            <i class="bx bx-user fs-48"></i>
                                        </span>
                                    </div>
                                    <h4 class="mb-1">{{ $user['name'] }}</h4>
                                    <p class="text-muted mb-3">{{ $user['email'] }}</p>
                                    <span class="badge bg-success fs-12">{{ $user['status'] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-8 col-lg-8 col-md-6">
                            <div class="card custom-card">
                                <div class="card-header">
                                    <div class="card-title">User Details</div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Phone Number</label>
                                                <p class="form-control-plaintext">{{ $user['phone'] }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Registration Date</label>
                                                <p class="form-control-plaintext">{{ $user['registration_date'] }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Last Login</label>
                                                <p class="form-control-plaintext">{{ $user['last_login'] }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Support Tickets</label>
                                                <p class="form-control-plaintext">{{ $user['support_tickets'] }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Resolved Tickets</label>
                                                <p class="form-control-plaintext">{{ $user['resolved_tickets'] }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Support Actions -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card custom-card">
                                <div class="card-header">
                                    <div class="card-title">Support Actions</div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <button class="btn btn-primary w-100 mb-3">
                                                <i class="bx bx-plus me-2"></i>Create Ticket
                                            </button>
                                        </div>
                                        <div class="col-md-3">
                                            <button class="btn btn-info w-100 mb-3">
                                                <i class="bx bx-message me-2"></i>Send Message
                                            </button>
                                        </div>
                                        <div class="col-md-3">
                                            <button class="btn btn-warning w-100 mb-3">
                                                <i class="bx bx-phone me-2"></i>Call User
                                            </button>
                                        </div>
                                        <div class="col-md-3">
                                            <button class="btn btn-success w-100 mb-3">
                                                <i class="bx bx-history me-2"></i>View History
                                            </button>
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
    <!-- End::row-1 -->
</div>
@endsection
