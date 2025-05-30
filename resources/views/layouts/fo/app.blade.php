<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/png" href="{{ asset('img/'.config('a1.uiux.favicon')) }}"/>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('a1.company.appname', 'Demo') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
    <link href="{{ asset('css/plugins.css') }}" rel="stylesheet">
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
    @yield('style')

</head>
<body class="hold-transition sidebar-mini layout-fixed">

<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-static navbar-static-top navbar-warning navbar-light">

    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-lg fa-bars"></i></a>
      </li>
    </ul>

    <ul class="navbar-nav">

      <!-- <li class="nav-item">
        <span class="navbar-link">@yield('content_title')</span>
      </li> -->

      <li class="nav-item">
        {{ Auth::user()->name }} - {{ Auth::user()->dept }}</span>
      </li>
      
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">

      <!-- Home -->
      <li class="nav-item">
        <a href="{{ route('absen') }}" class="nav-link">
          <i class="fas fa-lg fa-home"></i>
        </a>
      </li>

      <!-- Logout -->
      <li class="nav-item">
        <a href="{{ route('logout') }}" class="nav-link"
          onclick="event.preventDefault();
          document.getElementById('logout-form').submit();">
          <i class="fas fa-lg fa-sign-out-alt"></i>
        </a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>

      </li>
    
      @yield('toolbar')

    </ul>

  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('absen') }}" class="brand-link">
      <img id="logoFull" src="{{ asset('img/'.config('a1.uiux.logo')) }}" alt="{{ config('a1.company.appname','Demo') }}" class="brand-image elevation-3 logo-full">
      <img id="logoIcon" src="{{ asset('img/'.config('a1.uiux.favicon')) }}" alt="{{ config('a1.company.name','Demo') }}" class="brand-image elevation-3 logo-icon">
      <span class="brand-text font-weight-light"></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{ asset('img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">


          @if (Auth::check())
            <a href="{{ route('home.index') }}" class="d-block">{{ Auth::user()->name }}</a>
          @endif
          
        </div>
      </div>

      <!-- Sidebar Menu -->
      @include('layouts.fo.app-sidebar')
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" style="background-color: white;">

    <!-- Content Header (Page header) -->
    <div class="content-header" style="padding-top: 60px;">
      <div class="container-fluid">
        <!-- row mb-2 -->
        <div class="crud-container">

          <div class="crud-title">
            <h1 class="m-0 text-dark">@yield('content_header_title')</h1>
          </div><!-- /.col -->

          <div class="crud-toolbar crud-toolbar-end">
          </div>

        </div><!-- /.row -->

      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
          @yield('content')
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <strong>Copyright &copy; {{ config('a1.company.copyright', '2020') }} <a href="{{ config('a1.company.website', route('home.index')) }}">{{ config('a1.company.name', 'Demo') }}</a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> {{ config('a1.company.version','1.0') }}
    </div>
  </footer>

  <!-- Control Sidebar -->
  <aside class="p-3 control-sidebar control-sidebar-dark" style="opacity: 90%;">
    <!-- Control sidebar content goes here -->
    @yield('control_sidebar')
  </aside>
  <!-- /.control-sidebar -->
</div>


    <!-- Scripts -->
    <script src="{{ asset('js/manifest.js') }}"></script>
    <script src="{{ asset('js/vendor.js') }}"></script>
    <script src="{{ asset('js/admin.js') }}"></script>
    <script src="{{ asset('js/plugins.js') }}"></script>
    <script src="{{ asset('js/main.js') }}" defer></script>
    @yield('js')

</body>
</html>
