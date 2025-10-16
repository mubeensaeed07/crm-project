@extends('layouts.master')

@section('title', 'FINANCE - Budgets')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Budget Management</h1>
            <p class="text-muted">Create and manage budgets for different categories</p>
        </div>
        <div>
            <button class="btn btn-primary">
                <i class="fas fa-plus"></i> Create Budget
            </button>
        </div>
    </div>

    <!-- Budget Cards -->
    <div class="row mb-4">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Marketing Budget</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">$10,000</div>
                            <div class="text-xs text-muted">Used: $6,500 (65%)</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-bullhorn fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Operations Budget</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">$25,000</div>
                            <div class="text-xs text-muted">Used: $18,000 (72%)</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-cogs fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">HR Budget</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">$15,000</div>
                            <div class="text-xs text-muted">Used: $12,000 (80%)</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Budget Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Budget Overview</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>Budget Amount</th>
                            <th>Spent</th>
                            <th>Remaining</th>
                            <th>Utilization</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Marketing</td>
                            <td>$10,000.00</td>
                            <td>$6,500.00</td>
                            <td>$3,500.00</td>
                            <td>
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar" style="width: 65%"></div>
                                </div>
                                65%
                            </td>
                            <td><span class="badge badge-warning">On Track</span></td>
                            <td>
                                <button class="btn btn-sm btn-primary">View</button>
                                <button class="btn btn-sm btn-warning">Edit</button>
                            </td>
                        </tr>
                        <tr>
                            <td>Operations</td>
                            <td>$25,000.00</td>
                            <td>$18,000.00</td>
                            <td>$7,000.00</td>
                            <td>
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar" style="width: 72%"></div>
                                </div>
                                72%
                            </td>
                            <td><span class="badge badge-warning">On Track</span></td>
                            <td>
                                <button class="btn btn-sm btn-primary">View</button>
                                <button class="btn btn-sm btn-warning">Edit</button>
                            </td>
                        </tr>
                        <tr>
                            <td>HR</td>
                            <td>$15,000.00</td>
                            <td>$12,000.00</td>
                            <td>$3,000.00</td>
                            <td>
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar" style="width: 80%"></div>
                                </div>
                                80%
                            </td>
                            <td><span class="badge badge-danger">Over Budget</span></td>
                            <td>
                                <button class="btn btn-sm btn-primary">View</button>
                                <button class="btn btn-sm btn-warning">Edit</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
