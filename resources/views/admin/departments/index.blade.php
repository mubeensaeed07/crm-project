@extends('layouts.master')

@section('title', 'Departments Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Departments Management</h4>
                    <a href="{{ route('admin.departments.create') }}" class="btn btn-primary">
                        <i class="bx bx-plus"></i> Add New Department
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($departments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Code</th>
                                        <th>Description</th>
                                        <th>Status</th>
                                        <th>Users Count</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($departments as $department)
                                        <tr>
                                            <td>{{ $department->name }}</td>
                                            <td>
                                                <span class="badge bg-secondary">{{ $department->code }}</span>
                                            </td>
                                            <td>{{ $department->description ?? 'No description' }}</td>
                                            <td>
                                                @if($department->is_active)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-danger">Inactive</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ $department->userInfos()->count() }}</span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.departments.edit', $department->id) }}" 
                                                       class="btn btn-sm btn-outline-primary">
                                                        <i class="bx bx-edit"></i> Edit
                                                    </a>
                                                    <form action="{{ route('admin.departments.destroy', $department->id) }}" 
                                                          method="POST" class="d-inline"
                                                          onsubmit="return confirm('Are you sure you want to delete this department?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                                            <i class="bx bx-trash"></i> Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bx bx-building-house" style="font-size: 4rem; color: #ccc;"></i>
                            <h5 class="mt-3">No Departments Found</h5>
                            <p class="text-muted">Create your first department to get started.</p>
                            <a href="{{ route('admin.departments.create') }}" class="btn btn-primary">
                                <i class="bx bx-plus"></i> Create Department
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
