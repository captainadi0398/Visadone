{{-- @extends('layout.app') --}}

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Visadone Admin</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="{{ asset('.vendors/feather/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('.vendors/ti-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('.vendors/css/vendor.bundle.base.css') }}">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="{{ asset('.vendors/datatables.net-bs4/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('.js/select.dataTables.min.css') }}">

    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="{{ asset('./css/vertical-layout-light/style.css') }}">
    <!-- Option 1: Include in HTML -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css"
        integrity="sha384-b6lVK+yci+bfDmaY1u0zE8YYJt0TZxLEAFyYSLHId4xoVvsrQu3INevFKo+Xir8e" crossorigin="anonymous">
    <!-- endinject -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <link rel="shortcut icon" href="../images/visadone_logo - Copy.png" />
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            max-width: 100%;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            font-family: 'Open Sans', sans-serif;
        }

        th {
            background-color: #f2f2f2;
        }

        input[type="text"] {
            padding: 8px;
            border: 1px solid #ddd;
            margin-right: 10px;
        }

        #usermana {
            color: #ffffff;
            margin-bottom: 1.2rem;
            text-transform: capitalize;
            font-size: 1.125rem;
            font-weight: 700;
            font-family: system-ui;
            border-radius: 10px;
            padding: 10px;
            background: #3a37c7;
        }
    </style>
    <style>
        .toggle-switch {
            position: relative;
            display: inline-block;
            width: 40px;
            height: 20px;
            background-color: #ccc;
            border-radius: 10px;
            cursor: pointer;
        }

        .toggle-switch input {
            display: none;
        }

        .toggle-switch-slider {
            position: absolute;
            top: 1px;
            left: 1px;
            width: 18px;
            height: 18px;
            background-color: #fe7373;
            border-radius: 50%;
            transition: left 0.3s ease;
        }

        /* Style for the slider when the switch is on */
        .toggle-switch input:checked+.toggle-switch-slider {
            left: 21px;
            background-color: rgb(29, 150, 255);
            /* Change color when on */
        }
    </style>




    <script>
        function sortTable(n) {
            var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
            table = document.querySelector('.table');
            switching = true;
            // Set the sorting direction to ascending
            dir = 'asc';
            // Loop until no switching has been done
            while (switching) {
                switching = false;
                rows = table.rows;
                // Loop through all table rows (except the header row)
                for (i = 1; i < (rows.length - 1); i++) {
                    shouldSwitch = false;
                    x = rows[i].getElementsByTagName('TD')[n];
                    y = rows[i + 1].getElementsByTagName('TD')[n];
                    // Check if the two rows should switch places based on the sorting direction
                    if (dir === 'asc') {
                        if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                            shouldSwitch = true;
                            break;
                        }
                    } else if (dir === 'desc') {
                        if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                            shouldSwitch = true;
                            break;
                        }
                    }
                }
                if (shouldSwitch) {
                    // If a switch has been marked, make the switch and mark that a switch has been done
                    rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                    switching = true;
                    // Increase the switch count by 1
                    switchcount++;
                } else {
                    // If no switching has been done and the direction is 'asc', set the direction to 'desc' and run the loop again
                    if (switchcount === 0 && dir === 'asc') {
                        dir = 'desc';
                        switching = true;
                    }
                }
            }
        }
    </script>
</head>
<div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
    <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
        <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
            <a class="navbar-brand brand-logo mr-5" href="index.html"><img src="../images/visadone_logo.png"
                    class="mr-2" alt="logo" /></a>
            <a class="navbar-brand brand-logo-mini" href="index.html"><img src="../images/visadone_logo.png"
                    alt="logo" /></a>
        </div>
        <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
            <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
                <span class="icon-menu"></span>
            </button>
            <ul class="navbar-nav mr-lg-2">
                <li class="nav-item nav-search d-none d-lg-block">
                    <div class="input-group">
                        <div class="input-group-prepend hover-cursor" id="navbar-search-icon">
                            <span class="input-group-text" id="search">
                                <i class="icon-search"></i>
                            </span>
                        </div>
                        <input type="text" class="form-control" id="navbar-search-input" placeholder="Search now"
                            aria-label="search" aria-describedby="search">
                    </div>
                </li>
            </ul>
            <ul class="navbar-nav navbar-nav-right">
                <li class="nav-item dropdown">
                    <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#"
                        data-toggle="dropdown">
                        <i class="icon-bell mx-0"></i>
                        <span class="count"></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list"
                        aria-labelledby="notificationDropdown">
                        <p class="mb-0 font-weight-normal float-left dropdown-header">Notifications</p>
                        {{-- <a class="dropdown-item preview-item">
                                <div class="preview-thumbnail">
                                    <div class="preview-icon bg-success">
                                        <i class="ti-info-alt mx-0"></i>
                                    </div>
                                </div>
                                <div class="preview-item-content">
                                    <h6 class="preview-subject font-weight-normal">Application Error</h6>
                                    <p class="font-weight-light small-text mb-0 text-muted">
                                        Just now
                                    </p>
                                </div>
                            </a>
                            <a class="dropdown-item preview-item">
                                <div class="preview-thumbnail">
                                    <div class="preview-icon bg-warning">
                                        <i class="ti-settings mx-0"></i>
                                    </div>
                                </div>
                                <div class="preview-item-content">
                                    <h6 class="preview-subject font-weight-normal">Settings</h6>
                                    <p class="font-weight-light small-text mb-0 text-muted">
                                        Private message
                                    </p>
                                </div>
                            </a>
                            <a class="dropdown-item preview-item">
                                <div class="preview-thumbnail">
                                    <div class="preview-icon bg-info">
                                        <i class="ti-user mx-0"></i>
                                    </div>
                                </div>
                                <div class="preview-item-content">
                                    <h6 class="preview-subject font-weight-normal">New user registration</h6>
                                    <p class="font-weight-light small-text mb-0 text-muted">
                                        2 days ago
                                    </p>
                                </div>
                            </a> --}}
                    </div>
                </li>
                <li class="nav-item nav-profile dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
                        <img src="../images/faces/face.jpeg" alt="profile" />
                    </a>
                    <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
                        <a class="dropdown-item">
                            <i class="ti-settings text-primary"></i>
                            Settings
                        </a>
                        <a class="dropdown-item">
                            <i class="ti-power-off text-primary"></i>
                            Logout
                        </a>
                    </div>
                </li>
                <li class="nav-item nav-settings d-none d-lg-flex">
                    <a class="nav-link" href="#">
                        <i class="icon-ellipsis"></i>
                    </a>
                </li>
            </ul>
            <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
                data-toggle="offcanvas">
                <span class="icon-menu"></span>
            </button>
        </div>
    </nav>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
        <!-- partial:partials/_settings-panel.html -->
        <div class="theme-setting-wrapper">
            <div id="settings-trigger"><i class="ti-settings"></i></div>
            <div id="theme-settings" class="settings-panel">
                <i class="settings-close ti-close"></i>
                <p class="settings-heading">SIDEBAR SKINS</p>
                <div class="sidebar-bg-options selected" id="sidebar-light-theme">
                    <div class="img-ss rounded-circle bg-light border mr-3"></div>Light
                </div>
                <div class="sidebar-bg-options" id="sidebar-dark-theme">
                    <div class="img-ss rounded-circle bg-dark border mr-3"></div>Dark
                </div>
                <p class="settings-heading mt-2">HEADER SKINS</p>
                <div class="color-tiles mx-0 px-4">
                    <div class="tiles success"></div>
                    <div class="tiles warning"></div>
                    <div class="tiles danger"></div>
                    <div class="tiles info"></div>
                    <div class="tiles dark"></div>
                    <div class="tiles default"></div>
                </div>
            </div>
        </div>
        <div id="right-sidebar" class="settings-panel">
            <i class="settings-close ti-close"></i>
            <ul class="nav nav-tabs border-top" id="setting-panel" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="todo-tab" data-toggle="tab" href="#todo-section" role="tab"
                        aria-controls="todo-section" aria-expanded="true">TO DO LIST</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="chats-tab" data-toggle="tab" href="#chats-section" role="tab"
                        aria-controls="chats-section">CHATS</a>
                </li>
            </ul>
            <div class="tab-content" id="setting-content">
                <div class="tab-pane fade show active scroll-wrapper" id="todo-section" role="tabpanel"
                    aria-labelledby="todo-section">
                    <div class="add-items d-flex px-3 mb-0">
                        <form class="form w-100">
                            <div class="form-group d-flex">
                                <input type="text" class="form-control todo-list-input" placeholder="Add To-do">
                                <button type="submit" class="add btn btn-primary todo-list-add-btn"
                                    id="add-task">Add</button>
                            </div>
                        </form>
                    </div>
                    <div class="list-wrapper px-3">
                        <ul class="d-flex flex-column-reverse todo-list">
                            <li>
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input class="checkbox" type="checkbox">
                                        Team review meeting at 3.00 PM
                                    </label>
                                </div>
                                <i class="remove ti-close"></i>
                            </li>
                            <li>
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input class="checkbox" type="checkbox">
                                        Prepare for presentation
                                    </label>
                                </div>
                                <i class="remove ti-close"></i>
                            </li>
                            <li>
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input class="checkbox" type="checkbox">
                                        Resolve all the low priority tickets due today
                                    </label>
                                </div>
                                <i class="remove ti-close"></i>
                            </li>
                            <li class="completed">
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input class="checkbox" type="checkbox" checked>
                                        Schedule meeting for next week
                                    </label>
                                </div>
                                <i class="remove ti-close"></i>
                            </li>
                            <li class="completed">
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input class="checkbox" type="checkbox" checked>
                                        Project review
                                    </label>
                                </div>
                                <i class="remove ti-close"></i>
                            </li>
                        </ul>
                    </div>
                    <h4 class="px-3 text-muted mt-5 font-weight-light mb-0">Events</h4>
                    <div class="events pt-4 px-3">
                        <div class="wrapper d-flex mb-2">
                            <i class="ti-control-record text-primary mr-2"></i>
                            <span>Feb 11 2018</span>
                        </div>
                        <p class="mb-0 font-weight-thin text-gray">Creating component page build a js</p>
                        <p class="text-gray mb-0">The total number of sessions</p>
                    </div>
                    <div class="events pt-4 px-3">
                        <div class="wrapper d-flex mb-2">
                            <i class="ti-control-record text-primary mr-2"></i>
                            <span>Feb 7 2018</span>
                        </div>
                        <p class="mb-0 font-weight-thin text-gray">Meeting with Alisa</p>
                        <p class="text-gray mb-0 ">Call Sarah Graves</p>
                    </div>
                </div>
                <!-- To do section tab ends -->
                <div class="tab-pane fade" id="chats-section" role="tabpanel" aria-labelledby="chats-section">
                    <div class="d-flex align-items-center justify-content-between border-bottom">
                        <p class="settings-heading border-top-0 mb-3 pl-3 pt-0 border-bottom-0 pb-0">Friends</p>
                        <small
                            class="settings-heading border-top-0 mb-3 pt-0 border-bottom-0 pb-0 pr-3 font-weight-normal">See
                            All</small>
                    </div>
                    <ul class="chat-list">
                        <li class="list active">
                            <div class="profile"><img src="images/faces/face1.jpg" alt="image"><span
                                    class="online"></span></div>
                            <div class="info">
                                <p>Thomas Douglas</p>
                                <p>Available</p>
                            </div>
                            <small class="text-muted my-auto">19 min</small>
                        </li>
                        <li class="list">
                            <div class="profile"><img src="images/faces/face2.jpg" alt="image"><span
                                    class="offline"></span></div>
                            <div class="info">
                                <div class="wrapper d-flex">
                                    <p>Catherine</p>
                                </div>
                                <p>Away</p>
                            </div>
                            <div class="badge badge-success badge-pill my-auto mx-2">4</div>
                            <small class="text-muted my-auto">23 min</small>
                        </li>
                        <li class="list">
                            <div class="profile"><img src="images/faces/face3.jpg" alt="image"><span
                                    class="online"></span></div>
                            <div class="info">
                                <p>Daniel Russell</p>
                                <p>Available</p>
                            </div>
                            <small class="text-muted my-auto">14 min</small>
                        </li>
                        <li class="list">
                            <div class="profile"><img src="images/faces/face4.jpg" alt="image"><span
                                    class="offline"></span></div>
                            <div class="info">
                                <p>James Richardson</p>
                                <p>Away</p>
                            </div>
                            <small class="text-muted my-auto">2 min</small>
                        </li>
                        <li class="list">
                            <div class="profile"><img src="images/faces/face5.jpg" alt="image"><span
                                    class="online"></span></div>
                            <div class="info">
                                <p>Madeline Kennedy</p>
                                <p>Available</p>
                            </div>
                            <small class="text-muted my-auto">5 min</small>
                        </li>
                        <li class="list">
                            <div class="profile"><img src="images/faces/face6.jpg" alt="image"><span
                                    class="online"></span></div>
                            <div class="info">
                                <p>Sarah Graves</p>
                                <p>Available</p>
                            </div>
                            <small class="text-muted my-auto">47 min</small>
                        </li>
                    </ul>
                </div>
                <!-- chat tab ends -->
            </div>
        </div>
        <!-- partial -->
        <!-- partial:partials/_sidebar.html -->
        {{-- <nav class="sidebar sidebar-offcanvas" id="sidebar" style="">
            <div class="sidebar-wrapper">
                <ul class="nav">
                    <li class="nav-item">
                        <a class="nav-link" href="https://visadone.com/laravel_demo/laravel8/public/">
                            <i class="bi bi-speedometer"></i>
                            <span class="menu-title">&nbsp;Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('visa.add')}}">
                            <i class="bi bi-person-lines-fill"> </i>
                            <span class="menu-title">&nbsp;Apply Visa</span>
                        </a>
                    </li>


                    <li class="nav-item">
                        <a class="nav-link" href="{{route('track.list')}}">
                            <i class="bi bi-search"></i>
                            <span class="menu-title">&nbsp;Track Application</span>
                        </a>
                    </li>


                    <li class="nav-item">
                        <a class="nav-link" href="{{route('branch.list')}}">
                            <i class="bi bi-bar-chart-fill"></i>
                            <span class="menu-title">&nbsp;Branch Management</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('agent.list')}}">
                            <i class="bi bi-box-fill"></i>
                            <span class="menu-title">
                                Agent Management</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('agency.list')}}">
                            <i class="bi bi-file-bar-graph-fill"></i>
                            <span class="menu-title">
                                &nbsp;Agency Management</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{route('offer.list')}}">
                            <i class="bi bi-menu-down"></i>
                            <span class="menu-title">
                                &nbsp;Visa Offer Configuration</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('documentsget.list')}}">
                            <i class="bi bi-person-lines-fill"></i>
                            <span class="menu-title">
                                &nbsp;Document Rule Engine</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('users.list')}}">
                            <i class="bi bi-person-vcard-fill"></i>
                            <span class="menu-title">
                                &nbsp;User Management</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('user_role.list')}}">
                            <i class="bi bi-person-vcard-fill"></i>
                            <span class="menu-title">
                                &nbsp;User Role Config</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('currency.list')}}">
                            <i class="bi bi-currency-dollar"></i>
                            <span class="menu-title">
                                &nbsp;Currency Configuration</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('tax.list')}}">
                            <i class="bi bi-currency-dollar"></i>
                            <span class="menu-title">
                                &nbsp;Tax Configuration</span>
                        </a>
                    </li>

                </ul>
            </div>
        </nav> --}}
        <nav class="sidebar sidebar-offcanvas" id="sidebar" style="">
            <div class="sidebar-wrapper">
                <ul class="nav">
                    <li class="nav-item">
                        <a class="nav-link" href="/">
                            <i class="bi bi-speedometer"></i>
                            <span class="menu-title">&nbsp;Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="">
                            <i class="bi bi-person-lines-fill"> </i>
                            <span class="menu-title">&nbsp;Apply Visa</span>
                        </a>
                    </li>


                    <li class="nav-item">
                        <a class="nav-link" href="">
                            <i class="bi bi-search"></i>
                            <span class="menu-title">&nbsp;Track Application</span>
                        </a>
                    </li>


                    <li class="nav-item">
                        <a class="nav-link" href="">
                            <i class="bi bi-bar-chart-fill"></i>
                            <span class="menu-title">&nbsp;Branch Management</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="">
                            <i class="bi bi-box-fill"></i>
                            <span class="menu-title">
                                Agent Management</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="">
                            <i class="bi bi-file-bar-graph-fill"></i>
                            <span class="menu-title">
                                &nbsp;Agency Management</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="">
                            <i class="bi bi-menu-down"></i>
                            <span class="menu-title">
                                &nbsp;Visa Offer Configuration</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="">
                            <i class="bi bi-person-lines-fill"></i>
                            <span class="menu-title">
                                &nbsp;Document Rule Engine</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="">
                            <i class="bi bi-person-vcard-fill"></i>
                            <span class="menu-title">
                                &nbsp;User Management</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="">
                            <i class="bi bi-person-vcard-fill"></i>
                            <span class="menu-title">
                                &nbsp;User Role Config</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="">
                            <i class="bi bi-currency-dollar"></i>
                            <span class="menu-title">
                                &nbsp;Currency Configuration</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="">
                            <i class="bi bi-currency-dollar"></i>
                            <span class="menu-title">
                                &nbsp;Tax Configuration</span>
                        </a>
                    </li>

                </ul>
            </div>
        </nav>


        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body" style="background-color:none;">
                            <p class="card-title">@lang('Assign Permissions to Roles ') &nbsp;
                                {{-- <a href="/users/create" class="btn btn-success">@lang('Add Roles')</a> --}}
                            </p>

                            @if (session('message'))
                                <div class="alert alert-success">
                                    {{ session('message') }}
                                </div>
                            @endif

                            <!-- Add your form here -->
                            <form action="{{ route('perrole.update') }}" method="POST">
                                @csrf
                                <!-- Add your form fields here -->
                                <input type="hidden" value={{ $role_id }} name="role_id" />
                                <div class="row g-4">
                                    @foreach ($permission as $data)
                                        <div class="col-xl-4 col-lg-6 col-md-6 pt-3">
                                            <div class="card pt-3">
                                                <div class="card-body"
                                                    style="background-color: #f2fafd;border:0.5px solid black;border-radius:10px;">
                                                    <div class="d-flex justify-content-between mb-2">
                                                        <h6 class="fw-normal"> Permission For:</h6>
                                                        <ul
                                                            class="list-unstyled d-flex align-items-center avatar-group mb-0">
                                                            <li data-bs-toggle="tooltip" data-popup="tooltip-custom"
                                                                data-bs-placement="top"
                                                                class="avatar avatar-sm pull-up"
                                                                aria-label="Vinnie Mostowy"
                                                                data-bs-original-title="Vinnie Mostowy">
                                                                @php
                                                                    $assigned_permission_ids = $assigned_permission->pluck('permission_id')->toArray();
                                                                @endphp
                                                                <label class="toggle-switch"
                                                                    for="addSwitch_{{ $data->id }}"
                                                                    name="{{ $data->id }}">
                                                                    <input type="checkbox"
                                                                    {{ in_array($data->id, $assigned_permission_ids) ? 'checked' : '' }}
                                                                        class="custom-control"
                                                                        id="addSwitch_{{ $data->id }}"
                                                                        name="{{ $data->id }}">
                                                                    <span class="toggle-switch-slider"></span>
                                                                </label>

                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div class="d-flex justify-content-between align-items-end">
                                                        <div class="role-heading">
                                                            <h3 class="mb-1">{{ $data->name }}</h3>
                                                        </div>
                                                        <a href="javascript:void(0);" class="text-muted"><i
                                                                class="bx bx-copy"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Add the submit button again if needed -->
                                <button type="submit" class="btn btn-primary mt-3">Update</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            $(document).ready(function() {
                setTimeout(() => {
                    $('#loading-screen').css('display', 'none');
                }, 1000);

            });
        </script>

        <script>
            const table = document.getElementById('example1');
            const rows = table.getElementsByTagName('tr');
            const numColumns = table.rows[0].cells.length;

            // Create input fields for filtering dynamically
            const filterInputsContainer = document.createElement('div');
            filterInputsContainer.id = 'filterInputs';
            filterInputsContainer.className = 'row my-0';
            table.parentNode.insertBefore(filterInputsContainer, table);

            const columnWidths = ['100%', '100%', '100%', '100%', '100%', '100%', '100%', '100%'];
            const tableHeadings = Array.from(table.querySelectorAll('th')).map(th => th.innerText);

            for (let i = 0; i < numColumns; i++) {
                if (i === 6 || i === 7) continue; // Skip columns 7 and 8

                const inputDiv = document.createElement('div');
                inputDiv.className = 'col-md-2 mb-2';

                const input = document.createElement('input');
                input.type = 'text';
                input.placeholder = tableHeadings[i];
                input.className = 'form-control';
                input.style.borderRadius = '10px';
                input.style.fontFamily = 'Verdana, sans-serif';
                input.style.width = columnWidths[i];

                inputDiv.appendChild(input);

                filterInputsContainer.appendChild(inputDiv);
            }

            // Attach event listeners to input fields
            const filterInputs = filterInputsContainer.getElementsByTagName('input');
            for (const input of filterInputs) {
                input.addEventListener('input', filterTable);
            }

            function filterTable() {
                const filterValues = Array.from(filterInputs).map(input => input.value.toUpperCase());

                for (let i = 1; i < rows.length; i++) {
                    const cells = rows[i].getElementsByTagName('td');
                    let showRow = true;

                    for (let j = 0; j < cells.length; j++) {
                        if (j === 6 || j === 7) continue; // Skip columns 7 and 8

                        const cell = cells[j];
                        const cellText = cell.innerText.toUpperCase();
                        const filterValue = filterValues[j];

                        if (cellText.indexOf(filterValue) === -1) {
                            showRow = false;
                            break;
                        }
                    }

                    rows[i].style.display = showRow ? '' : 'none';
                }
            }
        </script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            $(document).ready(function() {
                setTimeout(() => {
                    $('#loading-screen').css('display', 'none');
                }, 100);

            });
        </script>
        <script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap4.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#example1').DataTable();

            });
        </script>
        <script src="vendors/js/vendor.bundle.base.js"></script>
        <!-- endinject -->
        <!-- Plugin js for this page -->
        <script src="vendors/chart.js/Chart.min.js"></script>
        <script src="vendors/datatables.net/jquery.dataTables.js"></script>
        <script src="vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>
        <script src="js/dataTables.select.min.js"></script>

        <!-- End plugin js for this page -->
        <!-- inject:js -->
        <script src="js/off-canvas.js"></script>
        <script src="js/hoverable-collapse.js"></script>
        <script src="js/template.js"></script>
        <script src="js/settings.js"></script>
        <script src="js/todolist.js"></script>
        <!-- endinject -->
        <!-- Custom js for this page-->
        <script src="js/dashboard.js"></script>
        <script src="js/Chart.roundedBarCharts.js"></script>
        <!-- page-body-wrapper ends -->
    </div>
