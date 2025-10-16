@extends('layouts.master')

@section('title') Support Tickets - SUPPORT @endsection

@section('content')
<div class="container-fluid">
    <!-- Start::row-1 -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">
                        Support Tickets
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
                                <h6>Support Tickets Management</h6>
                                <p class="mb-0">This is where you'll manage customer support tickets, track their status, and provide customer assistance.</p>
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
