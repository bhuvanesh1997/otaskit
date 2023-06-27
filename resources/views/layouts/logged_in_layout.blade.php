<!DOCTYPE html>
<html>
<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8">
    <title>Task</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="{{asset('admin_assets/vendors/styles/core.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('admin_assets/vendors/styles/icon-font.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('admin_assets/vendors/styles/style.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('admin_assets/src/plugins/datatables/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('admin_assets/src/plugins/datatables/css/responsive.bootstrap4.min.css')}}">
    <style type="text/css">
        .scroll
                {overflow-x:auto;}

    </style>
</head>
<body>
    <div class="header">
        <div class="header-left">
            
        </div>
        <div class="header-right">
            
        </div>
    </div>

    <div class="left-side-bar">
        <div class="brand-logo">
            <a href="{{URL::to('/dashboard')}}">
            </a>
        </div>
        <div class="menu-block customscroll">
            <div class="sidebar-menu">
                <ul id="accordion-menu">
                    <li>
                        <a href="{{URL::to('/dashboard')}}" class="dropdown-toggle no-arrow @if(Request::segment(2) == '') {{'active'}} @endif">
                            <span class="micon fa fa-home"></span><span class="mtext">Dashboard</span>
                        </a>
                    </li>
                    @guest

                    @else
                        @if(Auth::user()->privilage == 0)
                            <li>
                                <a href="{{ route('users') }}" class="dropdown-toggle no-arrow @if(Request::segment(2) == 'dashboard/users') {{'active'}} @endif">
                                    <span class="micon fa fa-user-plus"></span><span class="mtext">Users</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('manage_task') }}" class="dropdown-toggle no-arrow @if(Request::segment(2) == 'dashboard/manage_task') {{'active'}} @endif">
                                    <span class="micon fa fa-plus"></span><span class="mtext">Manage Task</span>
                                </a>
                            </li>
                        @endif

                        <li>
                            <a href="{{ route('assign_task') }}" class="dropdown-toggle no-arrow @if(Request::segment(2) == 'dashboard/assign_task') {{'active'}} @endif">
                                <span class="micon fa fa-plus-circle"></span><span class="mtext">
                                    @if(Auth::user()->privilage == 0)
                                        Assign Task
                                    @else
                                        Assigned Tasks
                                    @endif
                                </span>
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('logout') }}" class="dropdown-toggle no-arrow" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <span class="micon fa fa-user"></span><span class="mtext">Logout</span>
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </div>

    <div class="mobile-menu-overlay"></div>

    <div class="main-container">
        
        @yield('content')
        
    </div>
    
    <script src="{{asset('admin_assets/vendors/scripts/core.js')}}"></script>
    <script src="{{asset('admin_assets/vendors/scripts/script.min.js')}}"></script>
    <script src="{{asset('admin_assets/vendors/scripts/layout-settings.js')}}"></script>
    <script src="{{asset('admin_assets/src/plugins/datatables/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('admin_assets/src/plugins/datatables/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('admin_assets/src/plugins/datatables/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('admin_assets/src/plugins/datatables/js/responsive.bootstrap4.min.js')}}"></script>
    <!-- buttons for Export datatable -->
    <script src="{{asset('admin_assets/src/plugins/datatables/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{asset('admin_assets/src/plugins/datatables/js/buttons.bootstrap4.min.js')}}"></script>
    <script src="{{asset('admin_assets/src/plugins/datatables/js/buttons.print.min.js')}}"></script>
    <script src="{{asset('admin_assets/src/plugins/datatables/js/buttons.html5.min.js')}}"></script>
    <script src="{{asset('admin_assets/src/plugins/datatables/js/buttons.flash.min.js')}}"></script>
    <script src="{{asset('admin_assets/src/plugins/datatables/js/pdfmake.min.js')}}"></script>
    <script src="{{asset('admin_assets/src/plugins/datatables/js/vfs_fonts.js')}}"></script>
    <script src="{{asset('admin_assets/src/plugins/apexcharts/apexcharts.min.js')}}"></script>
    
    @yield('js')

</body>
</html>