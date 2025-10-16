@extends('support::layouts.support-master')

@section('title') User Support - SUPPORT @endsection

@section('content')
<div class="container-fluid">
    <!-- Start::row-1 -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">
                        User Support
                    </div>
                    <div class="card-tools">
                        <a href="{{ route('support.dashboard') }}" class="btn btn-secondary">
                            <i class="bx bx-arrow-back"></i> Back to Dashboard
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Search Section -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card custom-card">
                                <div class="card-header">
                                    <div class="card-title">Search Users</div>
                                </div>
                                <div class="card-body">
                                    <form id="searchUserForm">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="mb-3">
                                                    <label class="form-label">Search User</label>
                                                    <input type="text" class="form-control" id="userSearchInput" placeholder="Enter user name, email, or phone number">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="form-label">&nbsp;</label>
                                                    <button type="submit" class="btn btn-primary w-100">
                                                        <i class="bx bx-search me-2"></i>Search
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Search Results -->
                    <div class="row" id="searchResults" style="display: none;">
                        <div class="col-12">
                            <div class="card custom-card">
                                <div class="card-header">
                                    <div class="card-title">Search Results</div>
                                </div>
                                <div class="card-body">
                                    <div id="userResults">
                                        <!-- Results will be populated here -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End::row-1 -->
</div>

<script>
document.getElementById('searchUserForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const searchTerm = document.getElementById('userSearchInput').value;
    if (!searchTerm.trim()) {
        alert('Please enter a search term');
        return;
    }
    
    // Show loading
    document.getElementById('userResults').innerHTML = '<div class="text-center"><i class="bx bx-loader-alt bx-spin fs-24"></i> Searching...</div>';
    document.getElementById('searchResults').style.display = 'block';
    
    // Make API call
    fetch('{{ route("support.search.users") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ search: searchTerm })
    })
    .then(response => response.json())
    .then(data => {
        if (data.length > 0) {
            let html = '<div class="row">';
            data.forEach(user => {
                html += `
                    <div class="col-md-6 col-lg-4 mb-3">
                        <div class="card custom-card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <span class="avatar avatar-lg bg-primary-transparent">
                                            <i class="bx bx-user fs-20"></i>
                                        </span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">${user.name}</h6>
                                        <p class="text-muted mb-1">${user.email}</p>
                                        <p class="text-muted mb-0">${user.phone}</p>
                                        <span class="badge bg-success">${user.status}</span>
                                    </div>
                                    <div>
                                        <a href="{{ route('support.user.show', '') }}/${user.id}" class="btn btn-sm btn-primary">
                                            <i class="bx bx-show me-1"></i>View Profile
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });
            html += '</div>';
            document.getElementById('userResults').innerHTML = html;
        } else {
            document.getElementById('userResults').innerHTML = '<div class="text-center text-muted">No users found</div>';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('userResults').innerHTML = '<div class="text-center text-danger">Error searching users</div>';
    });
});
</script>
@endsection
