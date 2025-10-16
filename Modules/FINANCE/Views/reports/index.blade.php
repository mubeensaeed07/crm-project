@extends('layouts.master')

@section('title', 'FINANCE - Reports')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Financial Reports</h1>
            <p class="text-muted">Generate and view financial reports and analytics</p>
        </div>
        <div>
            <button class="btn btn-primary">
                <i class="fas fa-download"></i> Export Report
            </button>
        </div>
    </div>

    <!-- Report Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Monthly Revenue</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">$45,000</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Monthly Expenses</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">$28,000</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-bar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Net Profit</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">$17,000</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-pie fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Profit Margin</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">37.8%</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-percentage fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Report Types -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Available Reports</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="card border-left-primary">
                                <div class="card-body">
                                    <h6 class="card-title">Income Statement</h6>
                                    <p class="card-text">View revenue, expenses, and net profit</p>
                                    <button class="btn btn-primary btn-sm">Generate</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="card border-left-success">
                                <div class="card-body">
                                    <h6 class="card-title">Balance Sheet</h6>
                                    <p class="card-text">Assets, liabilities, and equity overview</p>
                                    <button class="btn btn-success btn-sm">Generate</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="card border-left-info">
                                <div class="card-body">
                                    <h6 class="card-title">Cash Flow Statement</h6>
                                    <p class="card-text">Track cash inflows and outflows</p>
                                    <button class="btn btn-info btn-sm">Generate</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="card border-left-warning">
                                <div class="card-body">
                                    <h6 class="card-title">Budget vs Actual</h6>
                                    <p class="card-text">Compare budgeted vs actual expenses</p>
                                    <button class="btn btn-warning btn-sm">Generate</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Stats</h6>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <div class="mb-3">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Total Users</div>
                            <div class="h4 mb-0 font-weight-bold text-gray-800">{{ $users->count() ?? 0 }}</div>
                        </div>
                        <div class="mb-3">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Active Users</div>
                            <div class="h4 mb-0 font-weight-bold text-gray-800">{{ $users->where('is_approved', true)->count() ?? 0 }}</div>
                        </div>
                        <div class="mb-3">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Total Salary</div>
                            <div class="h4 mb-0 font-weight-bold text-gray-800">${{ number_format($users->whereNotNull('salary')->sum('salary') ?? 0) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
