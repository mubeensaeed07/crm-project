@extends('support::layouts.support-master')

@section('title') Dealer Support - SUPPORT @endsection

@section('content')
<div class="container-fluid">
    <!-- Start::row-1 -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">
                        Dealer Support
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
                                    <div class="card-title">Search Dealers</div>
                                </div>
                                <div class="card-body">
                                    <form id="searchDealerForm">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="mb-3">
                                                    <label class="form-label">Search Dealer</label>
                                                    <input type="text" class="form-control" id="dealerSearchInput" placeholder="Enter dealer name, email, or location">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="form-label">&nbsp;</label>
                                                    <button type="submit" class="btn btn-success w-100">
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
                                    <div id="dealerResults">
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
document.getElementById('searchDealerForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const searchTerm = document.getElementById('dealerSearchInput').value;
    if (!searchTerm.trim()) {
        alert('Please enter a search term');
        return;
    }
    
    // Show loading
    document.getElementById('dealerResults').innerHTML = '<div class="text-center"><i class="bx bx-loader-alt bx-spin fs-24"></i> Searching...</div>';
    document.getElementById('searchResults').style.display = 'block';
    
    // Make API call
    fetch('{{ route("support.search.dealers") }}', {
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
            data.forEach(dealer => {
                html += `
                    <div class="col-md-6 col-lg-4 mb-3">
                        <div class="card custom-card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <span class="avatar avatar-lg bg-success-transparent">
                                            <i class="bx bx-store fs-20"></i>
                                        </span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">${dealer.name}</h6>
                                        <p class="text-muted mb-1">${dealer.email}</p>
                                        <p class="text-muted mb-1">${dealer.phone}</p>
                                        <p class="text-muted mb-0">${dealer.location}</p>
                                        <span class="badge bg-success">${dealer.status}</span>
                                    </div>
                                    <div>
                                        <a href="{{ route('support.dealer.show', '') }}/${dealer.id}" class="btn btn-sm btn-success">
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
            document.getElementById('dealerResults').innerHTML = html;
        } else {
            document.getElementById('dealerResults').innerHTML = '<div class="text-center text-muted">No dealers found</div>';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('dealerResults').innerHTML = '<div class="text-center text-danger">Error searching dealers</div>';
    });
});
</script>
@endsection
