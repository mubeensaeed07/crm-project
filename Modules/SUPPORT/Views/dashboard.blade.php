@extends('support::layouts.support-master')

@section('title') SUPPORT Dashboard @endsection

@section('content')
<div class="container-fluid">
    <!-- Start::row-1 -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">
                        SUPPORT Dashboard
                    </div>
                </div>
                <div class="card-body">
                    <!-- Two Main Options -->
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <div class="card custom-card">
                                <div class="card-body text-center">
                                    <div class="mb-4">
                                        <span class="avatar avatar-xxl bg-primary-transparent">
                                            <i class="bx bx-user fs-48"></i>
                                        </span>
                                    </div>
                                    <h4 class="mb-3">User Support</h4>
                                    <p class="text-muted mb-4">Provide support for individual users and customers</p>
                                    <a href="{{ route('support.user') }}" class="btn btn-primary btn-lg">
                                        <i class="bx bx-user me-2"></i>
                                        Access User Support
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <div class="card custom-card">
                                <div class="card-body text-center">
                                    <div class="mb-4">
                                        <span class="avatar avatar-xxl bg-success-transparent">
                                            <i class="bx bx-store fs-48"></i>
                                        </span>
                                    </div>
                                    <h4 class="mb-3">Dealer Support</h4>
                                    <p class="text-muted mb-4">Provide support for dealers and business partners</p>
                                    <a href="{{ route('support.dealer') }}" class="btn btn-success btn-lg">
                                        <i class="bx bx-store me-2"></i>
                                        Access Dealer Support
                                    </a>
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
