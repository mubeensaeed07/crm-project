@extends('layouts.master')

@section('title', 'FINANCE - Accounts')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Financial Accounts</h1>
            <p class="text-muted">Manage financial accounts and transactions</p>
        </div>
        <div>
            <button class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Account
            </button>
        </div>
    </div>

    <!-- Accounts Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Accounts Overview</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Account Name</th>
                            <th>Account Type</th>
                            <th>Balance</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Main Business Account</td>
                            <td>Checking</td>
                            <td>$25,000.00</td>
                            <td><span class="badge badge-success">Active</span></td>
                            <td>
                                <button class="btn btn-sm btn-primary">View</button>
                                <button class="btn btn-sm btn-warning">Edit</button>
                            </td>
                        </tr>
                        <tr>
                            <td>Savings Account</td>
                            <td>Savings</td>
                            <td>$50,000.00</td>
                            <td><span class="badge badge-success">Active</span></td>
                            <td>
                                <button class="btn btn-sm btn-primary">View</button>
                                <button class="btn btn-sm btn-warning">Edit</button>
                            </td>
                        </tr>
                        <tr>
                            <td>Investment Account</td>
                            <td>Investment</td>
                            <td>$100,000.00</td>
                            <td><span class="badge badge-success">Active</span></td>
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
