@extends('layouts.master')

@section('title', 'FINANCE Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3>FINANCE Dashboard</h3>
                </div>
                <div class="card-body">
                    <h4>Welcome to Finance Module!</h4>
                    <p>This is a simple Finance dashboard to test if the module is working.</p>
                    
                    <div class="row">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h5>Total Revenue</h5>
                                    <h3>$150,000</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h5>Total Expenses</h5>
                                    <h3>$75,000</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <h5>Net Profit</h5>
                                    <h3>$75,000</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <h5>Budget Utilization</h5>
                                    <h3>65%</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <a href="{{ route('finance.salaries') }}" class="btn btn-primary">Salary Management</a>
                        <a href="{{ route('finance.accounts') }}" class="btn btn-success">Accounts</a>
                        <a href="{{ route('finance.transactions') }}" class="btn btn-info">Transactions</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
