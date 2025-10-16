@extends('layouts.master')

@section('title', 'FINANCE - Transactions')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Financial Transactions</h1>
            <p class="text-muted">Track and manage financial transactions</p>
        </div>
        <div>
            <button class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Transaction
            </button>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Recent Transactions</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Description</th>
                            <th>Type</th>
                            <th>Amount</th>
                            <th>Account</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>2025-01-10</td>
                            <td>Office Rent Payment</td>
                            <td><span class="badge badge-danger">Expense</span></td>
                            <td>-$2,500.00</td>
                            <td>Main Business Account</td>
                            <td><span class="badge badge-success">Completed</span></td>
                            <td>
                                <button class="btn btn-sm btn-primary">View</button>
                                <button class="btn btn-sm btn-warning">Edit</button>
                            </td>
                        </tr>
                        <tr>
                            <td>2025-01-09</td>
                            <td>Client Payment Received</td>
                            <td><span class="badge badge-success">Income</span></td>
                            <td>+$5,000.00</td>
                            <td>Main Business Account</td>
                            <td><span class="badge badge-success">Completed</span></td>
                            <td>
                                <button class="btn btn-sm btn-primary">View</button>
                                <button class="btn btn-sm btn-warning">Edit</button>
                            </td>
                        </tr>
                        <tr>
                            <td>2025-01-08</td>
                            <td>Equipment Purchase</td>
                            <td><span class="badge badge-danger">Expense</span></td>
                            <td>-$1,200.00</td>
                            <td>Main Business Account</td>
                            <td><span class="badge badge-success">Completed</span></td>
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
