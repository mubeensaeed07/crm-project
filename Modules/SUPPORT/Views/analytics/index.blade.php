@extends('layouts.master')

@section('title') Support Analytics - SUPPORT @endsection

@section('content')
<div class="container-fluid">
    <!-- Start::row-1 -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">
                        Support Analytics
                    </div>
                    <div class="card-tools">
                        <a href="{{ route('support.dashboard') }}" class="btn btn-secondary">
                            <i class="bx bx-arrow-back"></i> Back to SUPPORT Dashboard
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <h6>Support Analytics & Reports</h6>
                                <p class="mb-0">This is where you'll view support metrics, ticket resolution times, customer satisfaction reports, and other analytics.</p>
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
