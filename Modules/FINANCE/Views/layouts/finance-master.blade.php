<!DOCTYPE html>
<html lang="en" dir="ltr" data-nav-layout="vertical" data-theme-mode="light" data-header-styles="light" data-menu-styles="dark" data-toggled="close">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Finance Dashboard - Ynex</title>
    <meta name="Description" content="Finance Management System">
    <meta name="Author" content="Spruko">
    <meta name="keywords" content="admin,admin dashboard,admin dashboard template,admin panel,admin template,admin theme,bootstrap admin,bootstrap admin dashboard,bootstrap admin panel,bootstrap admin template,bootstrap admin theme,bootstrap dashboard,bootstrap dashboard template,bootstrap dashboard theme,bootstrap template,bootstrap theme,dashboard,dashboard template,dashboard theme,laravel admin,laravel admin dashboard,laravel admin panel,laravel admin template,laravel dashboard,laravel dashboard template,laravel template,laravel theme,admin dashboard template,admin panel template,admin template,admin theme,bootstrap admin,bootstrap admin dashboard,bootstrap admin panel,bootstrap admin template,bootstrap admin theme,bootstrap dashboard,bootstrap dashboard template,bootstrap dashboard theme,bootstrap template,bootstrap theme,dashboard,dashboard template,dashboard theme,laravel admin,laravel admin dashboard,laravel admin panel,laravel admin template,laravel dashboard,laravel dashboard template,laravel template,laravel theme">

    <!-- Bootstrap Css -->
    <link id="style" href="{{asset('build/assets/libs/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">

    <!-- Icons Css -->
    <link href="{{asset('build/assets/icon-fonts/icons.css')}}" rel="stylesheet">
    
    <!-- APP SCSS -->
    @vite(['resources/sass/app.scss'])

    @include('layouts.components.styles')

    <link rel="icon" href="{{asset('build/assets/images/brand-logos/favicon.ico')}}" type="image/x-icon">
    <link rel="shortcut icon" type="image/x-icon" href="{{asset('build/assets/images/brand-logos/favicon.ico')}}">

    <!-- Internal Treeview Css -->
    <link href="{{asset('build/assets/plugins/treeview/treeview.css')}}" rel="stylesheet">
    
    <!-- Custom CSS for Profile Dropdown Fix -->
    <style>
        /* Force dropdown to be visible when show class is added */
        .header-profile-dropdown.show {
            display: block !important;
            position: absolute !important;
            top: 100% !important;
            left: 0 !important;
            z-index: 1050 !important;
            min-width: 200px !important;
            background: white !important;
            border: 1px solid #dee2e6 !important;
            border-radius: 0.375rem !important;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }
        
        /* Ensure dropdown menu is positioned correctly */
        .header-element {
            position: relative !important;
        }
        
        /* Fix for Bootstrap dropdown positioning */
        .main-header-dropdown {
            position: absolute !important;
            top: 100% !important;
            left: 0 !important;
            z-index: 1050 !important;
        }
    </style>

    <!-- Internal Chart Css -->
    <link href="{{asset('build/assets/plugins/chart/chart-c3.css')}}" rel="stylesheet">

    <!-- Internal DataTables Css -->
    <link href="{{asset('build/assets/plugins/datatable/css/dataTables.bootstrap5.min.css')}}" rel="stylesheet">
    <link href="{{asset('build/assets/plugins/datatable/responsive.bootstrap5.min.css')}}" rel="stylesheet">

    <!-- Internal Select2 Css -->
    <link href="{{asset('build/assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">

    <!-- Internal Sweet Alert Css -->
    <link href="{{asset('build/assets/plugins/sweet-alert/sweetalert.css')}}" rel="stylesheet">

    <!-- Internal Owl Carousel Css -->
    <link href="{{asset('build/assets/plugins/owl-carousel/owl-carousel.css')}}" rel="stylesheet">

    <!-- Internal Summernote Css -->
    <link href="{{asset('build/assets/plugins/summernote/summernote.css')}}" rel="stylesheet">

    <!-- Internal Phone Number Css -->
    <link href="{{asset('build/assets/plugins/telephoneinput/telephoneinput.css')}}" rel="stylesheet">

    <!-- Internal File Upload Css -->
    <link href="{{asset('build/assets/plugins/fileupload/css/fileupload.css')}}" rel="stylesheet">

    <!-- Internal Fancy Uploader Css -->
    <link href="{{asset('build/assets/plugins/fancyuploder/fancy_fileupload.css')}}" rel="stylesheet">

    <!-- Internal Multi Select Css -->
    <link href="{{asset('build/assets/plugins/multipleselect/multiple-select.css')}}" rel="stylesheet">

    <!-- Internal Nice Select Css -->
    <link href="{{asset('build/assets/plugins/niceselect/css/nice-select.css')}}" rel="stylesheet">

    <!-- Internal Jquery-Ui-Min Css -->
    <link href="{{asset('build/assets/plugins/jquery-ui/jquery-ui.css')}}" rel="stylesheet">

    <!-- Internal Time Picker Css -->
    <link href="{{asset('build/assets/plugins/time-picker/jquery.timepicker.css')}}" rel="stylesheet">

    <!-- Internal Date Picker Css -->
    <link href="{{asset('build/assets/plugins/date-picker/date-picker.css')}}" rel="stylesheet">

    <!-- Internal Daterangepicker Css -->
    <link href="{{asset('build/assets/plugins/daterangepicker/daterangepicker.css')}}" rel="stylesheet">

    <!-- Internal Spectrum Colorpicker Css -->
    <link href="{{asset('build/assets/plugins/spectrum-colorpicker/spectrum.css')}}" rel="stylesheet">

    <!-- Internal Dropzone Css -->
    <link href="{{asset('build/assets/plugins/dropzone/dropzone.css')}}" rel="stylesheet">

    <!-- Internal Css -->
    <link href="{{asset('build/assets/css/custom.css')}}" rel="stylesheet">

    @yield('styles')
</head>

<body class="main-body app sidebar-mini">
    <!-- Loader -->
    <div id="global-loader">
        <img src="{{asset('build/assets/images/loader.svg')}}" class="loader-img" alt="Loader">
    </div>
    <!-- /Loader -->

    <!-- Page -->
    <div class="page">
        <!-- HEADER -->
        @include('layouts.components.header')
        <!-- END HEADER -->

        <!-- FINANCE SIDEBAR -->
        <aside class="app-sidebar sticky" id="sidebar">
            <!-- Start::main-sidebar-header -->
            <div class="main-sidebar-header">
                <a href="{{url('index')}}" class="header-logo">
                    <img src="{{asset('build/assets/images/brand-logos/desktop-logo.png')}}" alt="logo" class="desktop-logo">
                    <img src="{{asset('build/assets/images/brand-logos/toggle-logo.png')}}" alt="logo" class="toggle-logo">
                    <img src="{{asset('build/assets/images/brand-logos/desktop-dark.png')}}" alt="logo" class="desktop-dark">
                    <img src="{{asset('build/assets/images/brand-logos/toggle-dark.png')}}" alt="logo" class="toggle-dark">
                    <img src="{{asset('build/assets/images/brand-logos/desktop-white.png')}}" alt="logo" class="desktop-white">
                    <img src="{{asset('build/assets/images/brand-logos/toggle-white.png')}}" alt="logo" class="toggle-white">
                </a>
            </div>
            <!-- End::main-sidebar-header -->

            <!-- Start::main-sidebar -->
            <div class="main-sidebar" id="sidebar-scroll">
                <!-- Start::nav -->
                <nav class="main-menu-container nav nav-pills flex-column sub-open">
                    <div class="slide-left" id="slide-left">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24"> <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z"></path> </svg>
                    </div>
                    <ul class="main-menu">
                        <!-- Start::slide__category -->
                        <li class="slide__category"><span class="category-name">Finance System</span></li>
                        <!-- End::slide__category -->

                        <!-- Back to Dashboard Link -->
                        <li class="slide">
                            <a href="{{ route(\App\Helpers\DashboardHelper::getDashboardRoute()) }}" class="side-menu__item">
                                <i class="bx bx-arrow-back side-menu__icon"></i>
                                <span class="side-menu__label">Back to Dashboard</span>
                            </a>
                        </li>

                        <!-- Finance Dashboard -->
                        <li class="slide">
                            <a href="{{ route('finance.dashboard') }}" class="side-menu__item {{ request()->routeIs('finance.dashboard') ? 'active' : '' }}">
                                <i class="bx bx-home side-menu__icon"></i>
                                <span class="side-menu__label">Finance Dashboard</span>
                            </a>
                        </li>

                        <!-- Finance Modules -->
                        <li class="slide has-sub">
                            <a href="javascript:void(0);" class="side-menu__item">
                                <i class="bx bx-money side-menu__icon"></i>
                                <span class="side-menu__label">Salary Management</span>
                                <i class="fe fe-chevron-right side-menu__angle"></i>
                            </a>
                            <ul class="slide-menu child1">
                                <li class="slide side-menu__label1">
                                    <a href="javascript:void(0);">Salary Management</a>
                                </li>
                                <li class="slide">
                                    <a href="{{ route('finance.salaries') }}" class="side-menu__item {{ request()->routeIs('finance.salaries') ? 'active' : '' }}">Salary Management</a>
                                </li>
                            </ul>
                        </li>

                        <!-- Finance Modules -->
                        <li class="slide has-sub">
                            <a href="javascript:void(0);" class="side-menu__item">
                                <i class="bx bx-grid-alt side-menu__icon"></i>
                                <span class="side-menu__label">Finance Modules</span>
                                <i class="fe fe-chevron-right side-menu__angle"></i>
                            </a>
                            <ul class="slide-menu child1">
                                <li class="slide side-menu__label1">
                                    <a href="javascript:void(0);">Finance Modules</a>
                                </li>
                                <li class="slide">
                                    <a href="{{ route('finance.accounts') }}" class="side-menu__item {{ request()->routeIs('finance.accounts') ? 'active' : '' }}">Accounts</a>
                                </li>
                                <li class="slide">
                                    <a href="{{ route('finance.transactions') }}" class="side-menu__item {{ request()->routeIs('finance.transactions') ? 'active' : '' }}">Transactions</a>
                                </li>
                                <li class="slide">
                                    <a href="{{ route('finance.budgets') }}" class="side-menu__item {{ request()->routeIs('finance.budgets') ? 'active' : '' }}">Budgets</a>
                                </li>
                                <li class="slide">
                                    <a href="{{ route('finance.reports') }}" class="side-menu__item {{ request()->routeIs('finance.reports') ? 'active' : '' }}">Reports</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    <div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24"> <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"></path> </svg></div>
                </nav>
                <!-- End::nav -->
            </div>
            <!-- End::main-sidebar -->
        </aside>
        <!-- End::main-sidebar -->

        <!-- Main Content-->
        <div class="main-content app-content">
            <div class="container-fluid">
                @yield('content')
            </div>
        </div>
        <!-- End Main Content-->
        
        <!-- PERMISSION WIDGET -->
        @include('layouts.components.permission-widget')
        <!-- END PERMISSION WIDGET -->

        <!-- Footer -->
        @include('layouts.components.footer')
        <!-- End Footer -->
    </div>
    <!-- End Page -->

    <!-- Back-to-top -->
    <a href="#top" id="back-to-top"><i class="ti ti-arrow-up"></i></a>

    <!-- Bootstrap JS -->
    <script src="{{asset('build/assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>

    <!-- APP JS -->
    @vite(['resources/js/app.js'])

    @include('layouts.components.scripts')

    @yield('scripts')
    
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
        // Show the professional modal
        try {
            if (document.getElementById('permissionAction')) {
                document.getElementById('permissionAction').textContent = action;
            }
            if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                var modal = new bootstrap.Modal(document.getElementById('permissionErrorModal'));
                modal.show();
            }
        } catch (error) {
            console.log('Bootstrap modal error:', error);
        }
    }
    
    // Force form submission for Mark Paid buttons
    function forceMarkPaid(userId) {
        if (confirm('Are you sure you want to mark this salary as paid?')) {
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = '/finance/salaries/' + userId + '/mark-paid';
            
            var csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            form.appendChild(csrfToken);
            
            document.body.appendChild(form);
            form.submit();
        }
    }
    
    // Fix profile dropdown functionality
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM Content Loaded - Initializing dropdowns');
        
        // Wait a bit for Bootstrap to be fully loaded
        setTimeout(function() {
            try {
                // Check if Bootstrap is available
                if (typeof bootstrap === 'undefined') {
                    console.error('Bootstrap is not loaded');
                    return;
                }
                
                // Initialize all dropdowns
                var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
                console.log('Found dropdown elements:', dropdownElementList.length);
                
                var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
                    try {
                        return new bootstrap.Dropdown(dropdownToggleEl);
                    } catch (error) {
                        console.error('Error initializing dropdown:', error);
                        return null;
                    }
                });
                
                // Specific profile dropdown initialization
                var profileDropdown = document.getElementById('mainHeaderProfile');
                if (profileDropdown) {
                    console.log('Profile dropdown found, adding click handler');
                    console.log('Profile dropdown element:', profileDropdown);
                    
                    // Remove any existing event listeners to prevent duplicates
                    profileDropdown.removeEventListener('click', handleProfileClick);
                    
                    // Add single click handler
                    profileDropdown.addEventListener('click', handleProfileClick);
                } else {
                    console.log('Profile dropdown not found - checking all elements with ID mainHeaderProfile');
                    console.log('All elements:', document.querySelectorAll('[id*="mainHeaderProfile"]'));
                }
                
            } catch (error) {
                console.error('Error in dropdown initialization:', error);
            }
        }, 100);
    });
    
    function handleProfileClick(e) {
        console.log('Profile dropdown clicked');
        e.preventDefault();
        e.stopPropagation();
        
        var dropdownMenu = document.querySelector('.header-profile-dropdown');
        console.log('Dropdown menu found:', dropdownMenu);
        
        if (dropdownMenu) {
            // Simple toggle logic
            if (dropdownMenu.classList.contains('show')) {
                console.log('Closing dropdown');
                dropdownMenu.classList.remove('show');
                dropdownMenu.style.display = 'none';
            } else {
                console.log('Opening dropdown');
                // Close any other open dropdowns first
                document.querySelectorAll('.dropdown-menu.show').forEach(function(menu) {
                    if (menu !== dropdownMenu) {
                        menu.classList.remove('show');
                        menu.style.display = 'none';
                    }
                });
                
                // Open this dropdown
                dropdownMenu.classList.add('show');
                dropdownMenu.style.display = 'block';
                dropdownMenu.style.position = 'absolute';
                dropdownMenu.style.top = '100%';
                dropdownMenu.style.left = '0';
                dropdownMenu.style.zIndex = '1050';
                dropdownMenu.style.minWidth = '200px';
                dropdownMenu.style.backgroundColor = 'white';
                dropdownMenu.style.border = '1px solid #dee2e6';
                dropdownMenu.style.borderRadius = '0.375rem';
                dropdownMenu.style.boxShadow = '0 0.5rem 1rem rgba(0, 0, 0, 0.15)';
            }
        } else {
            console.log('Dropdown menu not found!');
        }
    }
    
    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        // Only close if clicking outside the profile dropdown area
        if (!e.target.closest('#mainHeaderProfile') && !e.target.closest('.header-profile-dropdown')) {
            var dropdownMenu = document.querySelector('.header-profile-dropdown');
            if (dropdownMenu && dropdownMenu.classList.contains('show')) {
                console.log('Closing dropdown - clicked outside');
                dropdownMenu.classList.remove('show');
                dropdownMenu.style.display = 'none';
            }
        }
    });
    
    // Test function to manually show dropdown
    function testDropdown() {
        var dropdownMenu = document.querySelector('.header-profile-dropdown');
        if (dropdownMenu) {
            dropdownMenu.classList.add('show');
            dropdownMenu.style.display = 'block';
            dropdownMenu.style.position = 'absolute';
            dropdownMenu.style.top = '100%';
            dropdownMenu.style.left = '0';
            dropdownMenu.style.zIndex = '1050';
            console.log('Test dropdown shown');
        }
    }
    
    // Make test function globally available
    window.testDropdown = testDropdown;
    </script>
</body>

</html>
