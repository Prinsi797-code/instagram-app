<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>Rafi</title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no'
        name='viewport' />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i">
    <link rel="stylesheet" href="{{ asset('assets/css/ready.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}">
</head>
<style>
    /* Toast Notification Styles */
    .toast-container {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 9999;
        max-width: 400px;
    }

    .custom-toast {
        border: none;
        border-radius: 15px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        backdrop-filter: blur(10px);
        margin-bottom: 10px;
        overflow: hidden;
        animation: slideInRight 0.4s ease-out;
    }

    .custom-toast.success {
        background: linear-gradient(135deg, #4CAF50, #45a049);
        color: white;
        padding: 5px;
    }

    .custom-toast.error {
        background: linear-gradient(135deg, #f44336, #da190b);
        color: white;
    }

    .custom-toast.info {
        background: linear-gradient(135deg, #2196F3, #1976D2);
        color: white;
    }

    .custom-toast .toast-header {
        background: transparent;
        border: none;
        color: inherit;
        font-weight: 600;
    }

    .custom-toast .toast-body {
        padding: 15px 20px;
        font-size: 14px;
        line-height: 1.5;
    }

    .custom-toast .btn-close {
        filter: brightness(0) invert(1);
        opacity: 0.8;
    }

    .custom-toast .btn-close:hover {
        opacity: 1;
    }

    /* Progress bar for auto-hide */
    .toast-progress {
        position: absolute;
        bottom: 0;
        left: 0;
        height: 3px;
        background: rgba(255, 255, 255, 0.3);
        transition: width linear;
    }

    .toast-progress.success {
        background: rgba(255, 255, 255, 0.4);
    }

    .toast-progress.error {
        background: rgba(255, 255, 255, 0.4);
    }

    .toast-progress.info {
        background: rgba(255, 255, 255, 0.4);
    }

    .toast-hide {
        animation: slideOutRight 0.3s ease-in forwards;
    }

    /* Mobile responsiveness */
    @media (max-width: 768px) {
        .toast-container {
            left: 20px;
            right: 20px;
            max-width: none;
        }

        .custom-toast {
            margin-bottom: 8px;
        }
    }
</style>

<?php
$userCount = App\Models\User::count();
$couponCount = App\Models\GetCoupon::count();
?>

<body>
    <div class="wrapper">
        <div class="main-header">
            <div class="logo-header">
                <a href="index.html" class="logo">
                    Dashboard
                </a>
                <button class="navbar-toggler sidenav-toggler ml-auto" type="button" data-toggle="collapse"
                    data-target="collapse" aria-controls="sidebar" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <button class="topbar-toggler more"><i class="la la-ellipsis-v"></i></button>
            </div>
            <nav class="navbar navbar-header navbar-expand-lg ">
                <div class="container-fluid">

                    <form class="navbar-left navbar-form nav-search mr-md-3" action="">
                        <div class="input-group">
                            <input type="text" placeholder="Search ..." class="form-control">
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <i class="la la-search search-icon"></i>
                                </span>
                            </div>
                        </div>
                    </form>
                    {{-- <ul class="navbar-nav topbar-nav ml-md-auto align-items-center">
                        <li class="nav-item dropdown hidden-caret">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="la la-envelope"></i>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="#">Action</a>
                                <a class="dropdown-item" href="#">Another action</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#">Something else here</a>
                            </div>
                        </li> --}}
                    {{-- <li class="nav-item dropdown hidden-caret">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="la la-bell"></i>
                                <span class="notification">3</span>
                            </a>
                            <ul class="dropdown-menu notif-box" aria-labelledby="navbarDropdown">
                                <li>
                                    <div class="dropdown-title">You have 4 new notification</div>
                                </li>
                                <li>
                                    <div class="notif-center">
                                        <a href="#">
                                            <div class="notif-icon notif-primary"> <i class="la la-user-plus"></i>
                                            </div>
                                            <div class="notif-content">
                                                <span class="block">
                                                    New user registered
                                                </span>
                                                <span class="time">5 minutes ago</span>
                                            </div>
                                        </a>
                                        <a href="#">
                                            <div class="notif-icon notif-success"> <i class="la la-comment"></i> </div>
                                            <div class="notif-content">
                                                <span class="block">
                                                    Rahmad commented on Admin
                                                </span>
                                                <span class="time">12 minutes ago</span>
                                            </div>
                                        </a>
                                        <a href="#">
                                            <div class="notif-img">
                                                <img src="{{ asset('assets/img/profile2.jpg') }}" alt="Img Profile">
                                            </div>
                                            <div class="notif-content">
                                                <span class="block">
                                                    Reza send messages to you
                                                </span>
                                                <span class="time">12 minutes ago</span>
                                            </div>
                                        </a>
                                        <a href="#">
                                            <div class="notif-icon notif-danger"> <i class="la la-heart"></i> </div>
                                            <div class="notif-content">
                                                <span class="block">
                                                    Farrah liked Admin
                                                </span>
                                                <span class="time">17 minutes ago</span>
                                            </div>
                                        </a>
                                    </div>
                                </li>
                                <li>
                                    <a class="see-all" href="javascript:void(0);"> <strong>See all
                                            notifications</strong> <i class="la la-angle-right"></i> </a>
                                </li>
                            </ul>
                        </li> --}}
                    {{-- </ul> --}}
                </div>
            </nav>
            <div class="toast-container" id="toastContainer">
                <!-- Success Toast -->
                @if (session('success'))
                    <div class="toast custom-toast success show" role="alert" data-auto-hide="5000">
                        <div class="toast-header">
                            <i class="la la-exclamation-circle me-2"></i>
                            <strong class="me-auto">Success</strong>
                        </div>
                        <div class="toast-body">
                            {{ session('success') }}
                        </div>
                        <div class="toast-progress success"></div>
                    </div>
                @endif

                <!-- Error Toast -->
                @if (session('error'))
                    <div class="toast custom-toast error show" role="alert" data-auto-hide="6000">
                        <div class="toast-header">
                            <i class="la la-exclamation-circle me-2"></i>
                            <strong class="me-auto">Error</strong>
                        </div>
                        <div class="toast-body">
                            {{ session('error') }}
                        </div>
                        <div class="toast-progress error"></div>
                    </div>
                @endif
            </div>
        </div>
        <div class="sidebar">
            <div class="scrollbar-inner sidebar-wrapper">

                <ul class="nav">
                    <li class="nav-item active">
                        <a href="{{ route('home') }}">
                            <i class="la la-dashboard"></i>
                            <p>Dashboard</p>
                            <span class="badge badge-count"></span>
                        </a>
                    </li>
                    <li class="nav-item active">
                        <a href="{{ route('users') }}">
                            <i class="la la-user"></i>
                            <p>Users</p>
                            <span class="badge badge-count">{{ $userCount }}</span>
                        </a>
                    </li>
                    <li class="nav-item active">
                        <a href="{{ route('index') }}">
                            <i class="la la-viacoin"></i>
                            <p>Coupons</p>
                            <span class="badge badge-count">{{ $couponCount }}</span>
                        </a>
                    </li>
                    <li class="nav-item active">
                        <a href="{{ route('setting') }}">
                            <i class="la la-cog"></i>
                            <p>Settings</p>
                        </a>
                    </li>

                    <li class="nav-item active">
                        <a href="{{ route('purchase.user') }}">
                            <i class="la la-cog"></i>
                            <p>Purchase User</p>
                        </a>
                    </li>

                    <li class="nav-item active">
                        <a href="{{ route('logout') }}">
                            <i class="la la-unlock"></i>
                            <p>Logout</p>
                        </a>
                    </li>
                </ul>
            </div>
        </div>


        <div class="main-panel">
            @yield('content')
        </div>
        {{-- <div class="main-panel">
            <div class="content">
                <div class="container-fluid">
                    <h4 class="page-title">Dashboard</h4>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="card card-stats card-warning">
                                <div class="card-body ">
                                    <div class="row">
                                        <div class="col-5">
                                            <div class="icon-big text-center">
                                                <i class="la la-users"></i>
                                            </div>
                                        </div>
                                        <div class="col-7 d-flex align-items-center">
                                            <div class="numbers">
                                                <p class="card-category">Visitors</p>
                                                <h4 class="card-title">1,294</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card card-stats card-success">
                                <div class="card-body ">
                                    <div class="row">
                                        <div class="col-5">
                                            <div class="icon-big text-center">
                                                <i class="la la-bar-chart"></i>
                                            </div>
                                        </div>
                                        <div class="col-7 d-flex align-items-center">
                                            <div class="numbers">
                                                <p class="card-category">Sales</p>
                                                <h4 class="card-title">$ 1,345</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card card-stats card-danger">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-5">
                                            <div class="icon-big text-center">
                                                <i class="la la-newspaper-o"></i>
                                            </div>
                                        </div>
                                        <div class="col-7 d-flex align-items-center">
                                            <div class="numbers">
                                                <p class="card-category">Subscribers</p>
                                                <h4 class="card-title">1303</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card card-stats card-primary">
                                <div class="card-body ">
                                    <div class="row">
                                        <div class="col-5">
                                            <div class="icon-big text-center">
                                                <i class="la la-check-circle"></i>
                                            </div>
                                        </div>
                                        <div class="col-7 d-flex align-items-center">
                                            <div class="numbers">
                                                <p class="card-category">Order</p>
                                                <h4 class="card-title">576</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Task</h4>
                                    <p class="card-category">Complete</p>
                                </div>
                                <div class="card-body">
                                    <div id="task-complete" class="chart-circle mt-4 mb-3"></div>
                                </div>
                                <div class="card-footer">
                                    <div class="legend"><i class="la la-circle text-primary"></i> Completed</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">World Map</h4>
                                    <p class="card-category">
                                        Map of the distribution of users around the world</p>
                                </div>
                                <div class="card-body">
                                    <div class="mapcontainer">
                                        <div class="map">
                                            <span>Alternative content for the map</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row row-card-no-pd">
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <p class="fw-bold mt-1">My Balance</p>
                                    <h4><b>$ 3,018</b></h4>
                                    <a href="#" class="btn btn-primary btn-full text-left mt-3 mb-3"><i
                                            class="la la-plus"></i> Add Balance</a>
                                </div>
                                <div class="card-footer">
                                    <ul class="nav">
                                        <li class="nav-item"><a class="btn btn-default btn-link" href="#"><i
                                                    class="la la-history"></i> History</a></li>
                                        <li class="nav-item ml-auto"><a class="btn btn-default btn-link"
                                                href="#"><i class="la la-refresh"></i> Refresh</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="card">
                                <div class="card-body">
                                    <div class="progress-card">
                                        <div class="d-flex justify-content-between mb-1">
                                            <span class="text-muted">Profit</span>
                                            <span class="text-muted fw-bold"> $3K</span>
                                        </div>
                                        <div class="progress mb-2" style="height: 7px;">
                                            <div class="progress-bar bg-success" role="progressbar"
                                                style="width: 78%" aria-valuenow="78" aria-valuemin="0"
                                                aria-valuemax="100" data-toggle="tooltip" data-placement="top"
                                                title="78%"></div>
                                        </div>
                                    </div>
                                    <div class="progress-card">
                                        <div class="d-flex justify-content-between mb-1">
                                            <span class="text-muted">Orders</span>
                                            <span class="text-muted fw-bold"> 576</span>
                                        </div>
                                        <div class="progress mb-2" style="height: 7px;">
                                            <div class="progress-bar bg-info" role="progressbar" style="width: 65%"
                                                aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"
                                                data-toggle="tooltip" data-placement="top" title="65%"></div>
                                        </div>
                                    </div>
                                    <div class="progress-card">
                                        <div class="d-flex justify-content-between mb-1">
                                            <span class="text-muted">Tasks Complete</span>
                                            <span class="text-muted fw-bold"> 70%</span>
                                        </div>
                                        <div class="progress mb-2" style="height: 7px;">
                                            <div class="progress-bar bg-primary" role="progressbar"
                                                style="width: 70%" aria-valuenow="70" aria-valuemin="0"
                                                aria-valuemax="100" data-toggle="tooltip" data-placement="top"
                                                title="70%"></div>
                                        </div>
                                    </div>
                                    <div class="progress-card">
                                        <div class="d-flex justify-content-between mb-1">
                                            <span class="text-muted">Open Rate</span>
                                            <span class="text-muted fw-bold"> 60%</span>
                                        </div>
                                        <div class="progress mb-2" style="height: 7px;">
                                            <div class="progress-bar bg-warning" role="progressbar"
                                                style="width: 60%" aria-valuenow="60" aria-valuemin="0"
                                                aria-valuemax="100" data-toggle="tooltip" data-placement="top"
                                                title="60%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card card-stats">
                                <div class="card-body">
                                    <p class="fw-bold mt-1">Statistic</p>
                                    <div class="row">
                                        <div class="col-5">
                                            <div class="icon-big text-center icon-warning">
                                                <i class="la la-pie-chart text-warning"></i>
                                            </div>
                                        </div>
                                        <div class="col-7 d-flex align-items-center">
                                            <div class="numbers">
                                                <p class="card-category">Number</p>
                                                <h4 class="card-title">150GB</h4>
                                            </div>
                                        </div>
                                    </div>
                                    <hr />
                                    <div class="row">
                                        <div class="col-5">
                                            <div class="icon-big text-center">
                                                <i class="la la-heart-o text-primary"></i>
                                            </div>
                                        </div>
                                        <div class="col-7 d-flex align-items-center">
                                            <div class="numbers">
                                                <p class="card-category">Followers</p>
                                                <h4 class="card-title">+45K</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Users Statistics</h4>
                                    <p class="card-category">
                                        Users statistics this month</p>
                                </div>
                                <div class="card-body">
                                    <div id="monthlyChart" class="chart chart-pie"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">2018 Sales</h4>
                                    <p class="card-category">
                                        Number of products sold</p>
                                </div>
                                <div class="card-body">
                                    <div id="salesChart" class="chart"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header ">
                                    <h4 class="card-title">Table</h4>
                                    <p class="card-category">Users Table</p>
                                </div>
                                <div class="card-body">
                                    <table class="table table-head-bg-success table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">First</th>
                                                <th scope="col">Last</th>
                                                <th scope="col">Handle</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>Mark</td>
                                                <td>Otto</td>
                                                <td>@mdo</td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>Jacob</td>
                                                <td>Thornton</td>
                                                <td>@fat</td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td colspan="2">Larry the Bird</td>
                                                <td>@twitter</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card card-tasks">
                                <div class="card-header ">
                                    <h4 class="card-title">Tasks</h4>
                                    <p class="card-category">To Do List</p>
                                </div>
                                <div class="card-body ">
                                    <div class="table-full-width">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        <div class="form-check">
                                                            <label class="form-check-label">
                                                                <input class="form-check-input  select-all-checkbox"
                                                                    type="checkbox" data-select="checkbox"
                                                                    data-target=".task-select">
                                                                <span class="form-check-sign"></span>
                                                            </label>
                                                        </div>
                                                    </th>
                                                    <th>Task</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <div class="form-check">
                                                            <label class="form-check-label">
                                                                <input class="form-check-input task-select"
                                                                    type="checkbox">
                                                                <span class="form-check-sign"></span>
                                                            </label>
                                                        </div>
                                                    </td>
                                                    <td>Planning new project structure</td>
                                                    <td class="td-actions text-right">
                                                        <div class="form-button-action">
                                                            <button type="button" data-toggle="tooltip"
                                                                title="Edit Task"
                                                                class="btn btn-link <btn-simple-primary">
                                                                <i class="la la-edit"></i>
                                                            </button>
                                                            <button type="button" data-toggle="tooltip"
                                                                title="Remove" class="btn btn-link btn-simple-danger">
                                                                <i class="la la-times"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="form-check">
                                                            <label class="form-check-label">
                                                                <input class="form-check-input task-select"
                                                                    type="checkbox">
                                                                <span class="form-check-sign"></span>
                                                            </label>
                                                        </div>
                                                    </td>
                                                    <td>Update Fonts</td>
                                                    <td class="td-actions text-right">
                                                        <div class="form-button-action">
                                                            <button type="button" data-toggle="tooltip"
                                                                title="Edit Task"
                                                                class="btn btn-link <btn-simple-primary">
                                                                <i class="la la-edit"></i>
                                                            </button>
                                                            <button type="button" data-toggle="tooltip"
                                                                title="Remove" class="btn btn-link btn-simple-danger">
                                                                <i class="la la-times"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="form-check">
                                                            <label class="form-check-label">
                                                                <input class="form-check-input task-select"
                                                                    type="checkbox">
                                                                <span class="form-check-sign"></span>
                                                            </label>
                                                        </div>
                                                    </td>
                                                    <td>Add new Post
                                                    </td>
                                                    <td class="td-actions text-right">
                                                        <div class="form-button-action">
                                                            <button type="button" data-toggle="tooltip"
                                                                title="Edit Task"
                                                                class="btn btn-link <btn-simple-primary">
                                                                <i class="la la-edit"></i>
                                                            </button>
                                                            <button type="button" data-toggle="tooltip"
                                                                title="Remove" class="btn btn-link btn-simple-danger">
                                                                <i class="la la-times"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="form-check">
                                                            <label class="form-check-label">
                                                                <input class="form-check-input task-select"
                                                                    type="checkbox">
                                                                <span class="form-check-sign"></span>
                                                            </label>
                                                        </div>
                                                    </td>
                                                    <td>Finalise the design proposal</td>
                                                    <td class="td-actions text-right">
                                                        <div class="form-button-action">
                                                            <button type="button" data-toggle="tooltip"
                                                                title="Edit Task"
                                                                class="btn btn-link <btn-simple-primary">
                                                                <i class="la la-edit"></i>
                                                            </button>
                                                            <button type="button" data-toggle="tooltip"
                                                                title="Remove" class="btn btn-link btn-simple-danger">
                                                                <i class="la la-times"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="card-footer ">
                                    <div class="stats">
                                        <i class="now-ui-icons loader_refresh spin"></i> Updated 3 minutes ago
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <footer class="footer">
                <div class="container-fluid">
                    <nav class="pull-left">
                        <ul class="nav">
                            <li class="nav-item">
                                <a class="nav-link" href="http://www.themekita.com">
                                    ThemeKita
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">
                                    Help
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="https://themewagon.com/license/#free-item">
                                    Licenses
                                </a>
                            </li>
                        </ul>
                    </nav>
                    <div class="copyright ml-auto">
                        2018, made with <i class="la la-heart heart text-danger"></i> by <a
                            href="http://www.themekita.com">ThemeKita</a>
                    </div>
                </div>
            </footer>
        </div> --}}
    </div>
    </div>
    <!-- Modal -->
    {{-- <div class="modal fade" id="modalUpdate" tabindex="-1" role="dialog" aria-labelledby="modalUpdatePro"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h6 class="modal-title"><i class="la la-frown-o"></i> Under Development</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <p>Currently the pro version of the <b>Ready Dashboard</b> Bootstrap is in progress development</p>
                    <p>
                        <b>We'll let you know when it's done</b>
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div> --}}
</body>
<script src="{{ asset('assets/js/core/jquery.3.2.1.min.js') }}"></script>
<script src="{{ asset('assets/js/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js') }}"></script>
<script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
<script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/plugin/chartist/chartist.min.js') }}"></script>
<script src="{{ asset('assets/js/plugin/chartist/plugin/chartist-plugin-tooltip.min.js') }}"></script>
<script src="{{ asset('assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js') }}"></script>
<script src="{{ asset('assets/js/plugin/bootstrap-toggle/bootstrap-toggle.min.js') }}"></script>
<script src="{{ asset('assets/js/plugin/jquery-mapael/jquery.mapael.min.js') }}"></script>
<script src="{{ asset('assets/js/plugin/jquery-mapael/maps/world_countries.min.js') }}"></script>
<script src="{{ asset('assets/js/plugin/chart-circle/circles.min.js') }}"></script>
<script src="{{ asset('assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>
<script src="{{ asset('assets/js/ready.min.js') }}"></script>
<script src="{{ asset('assets/js/demo.js') }}"></script>

<script>
    function hideToast(toast) {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 500); // Smooth removal
    }

    document.addEventListener('DOMContentLoaded', () => {
        const toasts = document.querySelectorAll('.toast');

        toasts.forEach((toast) => {
            const progressBar = toast.querySelector('.toast-progress');
            const duration = parseInt(toast.getAttribute('data-auto-hide')) || 1000;

            if (progressBar) {
                progressBar.style.width = '100%';
                progressBar.style.transition = `width ${duration}ms linear`;
                setTimeout(() => {
                    progressBar.style.width = '0%';
                }, 100);
            }

            setTimeout(() => hideToast(toast), duration);

            toast.querySelector('.btn-close')?.addEventListener('click', () => hideToast(toast));
        });
    });
</script>

</html>
