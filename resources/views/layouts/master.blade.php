<!doctype html >
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark" data-layout="horizontal" data-topbar="dark" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable">

<head>
    <meta charset="utf-8" />
    <title>@yield('title') | Velzon - Admin & Dashboard Template</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ URL::asset('icon.png')}}">
    @include('layouts.head-css')
</head>

@section('body')
    @include('layouts.body')
@show
    <!-- Begin page -->
    <div id="layout-wrapper">
        @include('layouts.topbar')
        @include('layouts.sidebar')
        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    @yield('content')
                </div>
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->
            @include('layouts.footer')
        </div>
        <!-- end main content-->
    </div>
    <!-- END layout-wrapper -->

    @include('layouts.customizer')

    <!-- JAVASCRIPT -->
    @include('layouts.vendor-scripts')
    <script>
        document.addEventListener('submit', function(e) {
            const form = e.target;
            const btn = form.querySelector('button[type="submit"]');
            if (!btn) return;
            btn.disabled = true;
            const spinner = btn.querySelector('.spinner-border');
            const text = btn.querySelector('.btn-text');
            if (spinner) spinner.classList.remove('d-none');
            if (text) text.textContent = 'Menyimpan...';
        });
    </script>

</body>

</html>
