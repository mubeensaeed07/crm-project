@extends('layouts.master')

@section('title') Departments - HRM @endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">Department Management</div>
                    <div class="card-tools">
                        <a href="{{ route('hrm.dashboard') }}" class="btn btn-secondary">
                            <i class="bx bx-arrow-back"></i> Back to HRM Dashboard
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                            <div class="card custom-card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <span class="avatar avatar-md bg-primary-transparent">
                                                <i class="bx bx-group fs-18"></i>
                                            </span>
                                        </div>
                                        <div>
                                            <p class="mb-0 text-muted">Total Departments</p>
                                            <h4 class="mb-0 fw-semibold">{{ $departments->count() }}</h4>
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
                                                <i class="bx bx-user fs-18"></i>
                                            </span>
                                        </div>
                                        <div>
                                            <p class="mb-0 text-muted">Total Employees</p>
                                            <h4 class="mb-0 fw-semibold">{{ $users->count() }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-12">
                            <div class="card custom-card">
                                <div class="card-header">
                                    <div class="card-title">Department List</div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Department Code</th>
                                                    <th>Department Name</th>
                                                    <th>Description</th>
                                                    <th>Employee Count</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($departments as $department)
                                                <tr>
                                                    <td><span class="badge bg-primary">{{ $department['code'] }}</span></td>
                                                    <td>{{ $department['name'] }}</td>
                                                    <td>{{ \App\Models\Department::find($department['id'])->description ?? 'No description' }}</td>
                                                    <td>{{ $department['count'] }}</td>
                                                    <td><span class="badge bg-success">Active</span></td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="5" class="text-center">No departments found</td>
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
</div>
@endsection
