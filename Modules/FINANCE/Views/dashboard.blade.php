@extends('finance::layouts.finance-master')

@section('content')
<div class="container-fluid">
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0 text-gray-800">FINANCE Dashboard</h1>
        <p class="text-muted">Financial Management System - Last Updated: {{ now()->format('M d, Y H:i') }}</p>
    </div>
    <div>
        <button class="btn btn-primary" onclick="location.reload()">
            <i class="fas fa-sync-alt"></i> Refresh Data
        </button>
    </div>
</div>

<!-- Overdue Payments Alert -->
@if(isset($overdueUsers) && $overdueUsers->count() > 0)
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <h4 class="alert-heading">
        <i class="fas fa-exclamation-triangle"></i> Overdue Payments Alert!
    </h4>
    <p class="mb-2">The following employees have overdue salary payments that require immediate attention:</p>
    <ul class="mb-2">
        @foreach($overdueUsers as $user)
        <li><strong>{{ $user->full_name }}</strong> - {{ $user->email }}</li>
        @endforeach
    </ul>
    <p class="mb-0">
        <strong>Action Required:</strong> Please process these payments and mark them as "Paid" in the table below.
    </p>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<!-- Your Permissions Section -->
@php
    $currentUser = null;
    $permissions = [];
    $isSupervisor = false;
    
    // Check if user is supervisor
    if (Auth::guard('supervisor')->check()) {
        $currentUser = Auth::guard('supervisor')->user();
        $isSupervisor = true;
        
        // Get Finance module permissions (module ID 2)
        $modulePermission = $currentUser->permissions()->where('module_id', 2)->first();
        if ($modulePermission) {
            $permissions = [
                'can_view_reports' => $modulePermission->can_view_reports,
                'can_mark_salary_paid' => $modulePermission->can_mark_salary_paid ?? false,
                'can_mark_salary_pending' => $modulePermission->can_mark_salary_pending ?? false,
                'can_view_salary_data' => $modulePermission->can_view_salary_data ?? false,
                'can_manage_salary_payments' => $modulePermission->can_manage_salary_payments ?? false,
            ];
        }
    } else {
        // Check if user is regular user
        $currentUser = Auth::user();
        if ($currentUser) {
            // Get Finance module permissions (module ID 2)
            $modulePermission = $currentUser->modules()->where('module_id', 2)->first();
            if ($modulePermission) {
                $permissions = [
                    'can_view_reports' => $modulePermission->pivot->can_view_reports ?? false,
                    'can_mark_salary_paid' => $modulePermission->pivot->can_mark_salary_paid ?? false,
                    'can_mark_salary_pending' => $modulePermission->pivot->can_mark_salary_pending ?? false,
                    'can_view_salary_data' => $modulePermission->pivot->can_view_salary_data ?? false,
                    'can_manage_salary_payments' => $modulePermission->pivot->can_manage_salary_payments ?? false,
                ];
            }
        }
    }
    
    // Filter to only show permissions that are enabled
    $enabledPermissions = array_filter($permissions, function($value) {
        return $value === true;
    });
@endphp

@if($currentUser && !empty($enabledPermissions))
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-left-success shadow">
            <div class="card-header bg-success text-white">
                <div class="d-flex align-items-center">
                    <i class="bx bx-shield-check me-2"></i>
                    <h6 class="mb-0">Your Finance Permissions</h6>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($enabledPermissions as $permission => $enabled)
                        @if($enabled)
                            <div class="col-md-4 col-sm-6 mb-2">
                                <div class="d-flex align-items-center">
                                    <i class="bx bx-check-circle text-success me-2"></i>
                                    <span class="text-muted">
                                        @switch($permission)
                                            @case('can_view_reports')
                                                View Reports
                                                @break
                                            @case('can_mark_salary_paid')
                                                Mark Salary Paid
                                                @break
                                            @case('can_mark_salary_pending')
                                                Mark Salary Pending
                                                @break
                                            @case('can_view_salary_data')
                                                View Salary Data
                                                @break
                                            @case('can_manage_salary_payments')
                                                Manage Salary Payments
                                                @break
                                            @default
                                                {{ ucwords(str_replace('_', ' ', $permission)) }}
                                        @endswitch
                                    </span>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endif


<!-- Salary Management Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Users</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalUsers }}</div>
                        <div class="text-xs text-muted">Active employees</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
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
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Salary</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">${{ number_format($totalSalary, 2) }}</div>
                        <div class="text-xs text-muted">Monthly payroll</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
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
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Paid Salaries</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">${{ number_format($paidSalaries, 2) }}</div>
                        <div class="text-xs text-muted">Completed payments</div>
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
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pending Salaries</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">${{ number_format($pendingSalaries, 2) }}</div>
                        <div class="text-xs text-muted">Awaiting payment</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clock fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- User Salary Management Table -->
<div class="row">
    <div class="col-lg-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Salary Management</h6>
                <small class="text-muted">Showing users and supervisors only (Admins and SuperAdmins are excluded as they manage salaries)</small>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered text-nowrap">
                        <thead>
                            <tr>
                                <th class="border-bottom-0">Name</th>
                                <th class="border-bottom-0">Email</th>
                                <th class="border-bottom-0">Department</th>
                                <th class="border-bottom-0">Salary</th>
                                <th class="border-bottom-0">Status</th>
                                <th class="border-bottom-0">Next Payment</th>
                                <th class="border-bottom-0">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                            <tr>
                                <td>{{ $user->full_name ?? 'N/A' }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if($user->userInfo && $user->userInfo->department_id)
                                        @php
                                            // Try to get department via relationship first
                                            $department = $user->userInfo->department;
                                            if (!$department) {
                                                // Fallback to direct lookup
                                                $department = \App\Models\Department::find($user->userInfo->department_id);
                                            }
                                        @endphp
                                        {{ $department ? $department->name : 'N/A' }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>
                                    @if($user->userInfo && $user->userInfo->salary > 0)
                                        ${{ number_format($user->userInfo->salary, 2) }}
                                    @else
                                        <span class="text-muted">No salary set</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $salaryPayment = \App\Models\SalaryPayment::where('user_id', $user->id)->latest()->first();
                                    @endphp
                                    @if($salaryPayment && $salaryPayment->status == 'paid')
                                        <span class="badge bg-success">Paid</span>
                                    @else
                                        <span class="badge bg-warning">Pending</span>
                                    @endif
                                </td>
                                <td>
                                    @if($salaryPayment && $salaryPayment->next_payment_date)
                                        <span class="text-info">{{ $salaryPayment->next_payment_date->format('M d, Y') }}</span>
                                    @else
                                        <span class="text-muted">Not set</span>
                                    @endif
                                </td>
                                <td>
                                    @if($salaryPayment && $salaryPayment->status == 'paid')
                                        @if(hasPermission(2, 'can_mark_salary_pending'))
                                            <form action="{{ route('finance.salaries.mark-pending', $user->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-warning btn-sm">Mark Pending</button>
                                            </form>
                                        @else
                                            <button class="btn btn-warning btn-sm" onclick="showPermissionError('mark salary pending')">Mark Pending</button>
                                        @endif
                                    @else
                                        @if(hasPermission(2, 'can_mark_salary_paid'))
                                            <button class="btn btn-success btn-sm" onclick="forceMarkPaid({{ $user->id }})">Mark Paid</button>
                                        @else
                                            <button class="btn btn-success btn-sm" onclick="showPermissionError('mark salary paid')">Mark Paid</button>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">No users found</td>
                                </tr>
                                @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Payment History Section -->
@if(isset($recentPayments) && $recentPayments->count() > 0)
<div class="row">
    <div class="col-lg-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Payment History - {{ now()->format('F Y') }}</h6>
                <small class="text-muted">Recent salary payments for this month</small>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered text-nowrap">
                        <thead>
                            <tr>
                                <th class="border-bottom-0">Employee</th>
                                <th class="border-bottom-0">Amount</th>
                                <th class="border-bottom-0">Status</th>
                                <th class="border-bottom-0">Paid By</th>
                                <th class="border-bottom-0">Payment Date</th>
                                <th class="border-bottom-0">Notes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentPayments as $payment)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="me-2">
                                            <div class="avatar avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center">
                                                {{ substr($payment->user->full_name ?? 'U', 0, 1) }}
                                            </div>
                                        </div>
                                        <div>
                                            <div class="fw-semibold">{{ $payment->user->full_name ?? 'N/A' }}</div>
                                            <small class="text-muted">{{ $payment->user->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="fw-bold text-success">${{ number_format($payment->amount, 2) }}</span>
                                </td>
                                <td>
                                    @if($payment->status === 'paid')
                                        <span class="badge bg-success">Paid</span>
                                    @elseif($payment->status === 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @else
                                        <span class="badge bg-danger">Overdue</span>
                                    @endif
                                </td>
                                <td>
                                    <div>
                                        <div class="fw-semibold">{{ $payment->paid_by_name }}</div>
                                        <small class="text-muted">{{ ucfirst($payment->paid_by_type) }}</small>
                                    </div>
                                </td>
                                <td>
                                    @if($payment->paid_at)
                                        <span class="text-success">{{ $payment->paid_at->format('M d, Y H:i') }}</span>
                                    @else
                                        <span class="text-muted">Not paid yet</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="text-muted">{{ $payment->notes ?? 'No notes' }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                @if(isset($monthlyStats))
                <div class="row mt-4">
                    <div class="col-md-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body text-center">
                                <h5 class="card-title">{{ $monthlyStats['totalPayments'] }}</h5>
                                <p class="card-text">Total Payments</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                            <div class="card-body text-center">
                                <h5 class="card-title">${{ number_format($monthlyStats['paidAmount'], 2) }}</h5>
                                <p class="card-text">Paid Amount</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body text-center">
                                <h5 class="card-title">${{ number_format($monthlyStats['pendingAmount'], 2) }}</h5>
                                <p class="card-text">Pending Amount</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-danger text-white">
                            <div class="card-body text-center">
                                <h5 class="card-title">${{ number_format($monthlyStats['overdueAmount'], 2) }}</h5>
                                <p class="card-text">Overdue Amount</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endif

<!-- Quick Actions -->
<div class="row">
    <div class="col-lg-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('finance.salaries') }}" class="btn btn-primary btn-block">
                            <i class="fas fa-money-bill-wave"></i> Salary Management
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('finance.accounts') }}" class="btn btn-success btn-block">
                            <i class="fas fa-university"></i> Manage Accounts
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('finance.transactions') }}" class="btn btn-info btn-block">
                            <i class="fas fa-exchange-alt"></i> Transactions
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('finance.reports') }}" class="btn btn-warning btn-block">
                            <i class="fas fa-file-alt"></i> Reports
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Auto-refresh data every 30 seconds
    setInterval(function() {
        // Update timestamp
        const now = new Date();
        const timestamp = now.toLocaleString();
        document.querySelector('.text-muted').textContent = 'Financial Management System - Last Updated: ' + timestamp;
    }, 30000);

    // Add loading states to buttons
    document.addEventListener('DOMContentLoaded', function() {
        const buttons = document.querySelectorAll('button[type="submit"]');
        buttons.forEach(button => {
            button.addEventListener('click', function() {
                const originalText = this.innerHTML;
                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
                this.disabled = true;
                
                // Re-enable button after 3 seconds if form doesn't submit
                setTimeout(() => {
                    this.innerHTML = originalText;
                    this.disabled = false;
                }, 3000);
            });
        });
    });

    // Add confirmation for salary actions
    document.addEventListener('DOMContentLoaded', function() {
        const forms = document.querySelectorAll('form[action*="mark-paid"], form[action*="mark-pending"]');
        forms.forEach(form => {
            form.addEventListener('submit', function(e) {
                const action = this.action.includes('mark-paid') ? 'mark as paid' : 'mark as pending';
                if (!confirm(`Are you sure you want to ${action} this salary?`)) {
                    e.preventDefault();
                }
            });
        });
    });

</script>

<!-- Permission Error Modal -->
<div class="modal fade" id="permissionErrorModal" tabindex="-1" aria-labelledby="permissionErrorModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="permissionErrorModalLabel">
                    <i class="bx bx-shield-x me-2"></i>Permission Required
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <i class="bx bx-shield-x text-danger" style="font-size: 3rem;"></i>
                    <h4 class="mt-3 text-danger">Access Denied</h4>
                    <p class="text-muted">You do not have permission to <span id="permissionAction"></span>.</p>
                    <p class="text-muted">Please contact your administrator to request access.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
function showPermissionError(action) {
    document.getElementById('permissionAction').textContent = action;
    var modal = new bootstrap.Modal(document.getElementById('permissionErrorModal'));
    modal.show();
}
</script>
@endsection
