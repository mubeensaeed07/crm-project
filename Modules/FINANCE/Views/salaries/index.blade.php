@extends('finance::layouts.finance-master')

@section('title', 'Salary Management')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Salary Management</h1>
            <p class="text-muted">Manage employee salary payments</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Salary</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">${{ number_format($totalSalary ?? 0) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Paid</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">${{ number_format($totalPaid ?? 0) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pending</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">${{ number_format($totalPending ?? 0) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Users</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $users->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Salary List -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Employee Salaries</h6>
                </div>
                <div class="card-body">
                    @if($users->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Employee</th>
                                        <th>Salary</th>
                                        <th>Status</th>
                                        <th>Paid By</th>
                                        <th>Paid At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                        @if($user->userInfo && $user->userInfo->salary)
                                            @php
                                                $payment = $salaryPayments->where('user_id', $user->id)->where('status', 'paid')->first();
                                            @endphp
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="me-3">
                                                            <div class="avatar avatar-sm bg-primary-transparent">
                                                                <i class="bx bx-user fs-16"></i>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-0">{{ $user->first_name }} {{ $user->last_name }}</h6>
                                                            <small class="text-muted">{{ $user->email }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="fw-semibold">${{ number_format($user->userInfo->salary) }}</span>
                                                </td>
                                                <td>
                                                    @if($payment)
                                                        <span class="badge bg-success">Paid</span>
                                                    @else
                                                        <span class="badge bg-warning">Pending</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($payment && $payment->paidBy)
                                                        {{ $payment->paidBy->first_name }} {{ $payment->paidBy->last_name }}
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($payment && $payment->paid_at)
                                                        {{ $payment->paid_at->format('M d, Y H:i') }}
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($payment)
                                                        <form method="POST" action="{{ route('finance.salaries.mark-pending', $user->id) }}" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-warning btn-sm" onclick="return confirm('Are you sure you want to mark this salary as pending?')">
                                                                <i class="bx bx-undo"></i> Mark Pending
                                                            </button>
                                                        </form>
                                                    @else
                                                        <form method="POST" action="{{ route('finance.salaries.mark-paid', $user->id) }}" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Are you sure you want to mark this salary as paid?')">
                                                                <i class="bx bx-check"></i> Mark Paid
                                                            </button>
                                                        </form>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bx bx-user-x fs-48 text-muted"></i>
                            <h5 class="mt-3">No employees found</h5>
                            <p class="text-muted">No employees with salary information found.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable({
            "pageLength": 25,
            "order": [[ 0, "asc" ]]
        });
    });
</script>
@endsection
