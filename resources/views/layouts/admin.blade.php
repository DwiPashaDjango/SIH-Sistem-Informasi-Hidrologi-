<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>@yield('title') &mdash; SIH3 BWS Sumatera VI</title>
    <link rel="icon" href="{{asset('icon.ico')}}">
    <!-- General CSS Files -->
    <link rel="stylesheet" href="{{asset('admin')}}/modules/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{asset('admin')}}/modules/fontawesome/css/all.min.css">

    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{asset('admin')}}/modules/datatables/datatables.min.css">
    <link rel="stylesheet" href="{{asset('admin')}}/modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css">

    @stack('css')

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{asset('admin')}}/css/style.css">
    <link rel="stylesheet" href="{{asset('admin')}}/css/components.css">
    <!-- Start GA -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-94034622-3"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'UA-94034622-3');
    </script>
    <!-- /END GA -->
</head>

@if (Request::is('post/klimatologi/*/show'))
    <body class="sidebar-mini">
@elseif(Request::is('history/klimatologis'))
    <body class="sidebar-mini">
@else
    <body class="">
@endif
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            <div class="navbar-bg"></div>
            @include('components.admin.topbar')
            
            @include('components.admin.sidebar')

            <!-- Main Content -->
            <div class="main-content">
                <section class="section">
                    <div class="section-header">
                        <h1>@yield('title')</h1>
                    </div>

                    <div class="section-body">
                        @yield('content')
                    </div>
                </section>
            </div>
            <footer class="main-footer">
                <div class="footer-left">
                    Copyright &copy; {{date('Y')}}  BALAI WILAYAH SUNGAI SUMATERA VI
                </div>
                <div class="footer-right">

                </div>
            </footer>
        </div>
    </div>

    <!-- General JS Scripts -->
    <script src="{{asset('admin')}}/modules/jquery.min.js"></script>
    <script src="{{asset('admin')}}/modules/popper.js"></script>
    <script src="{{asset('admin')}}/modules/tooltip.js"></script>
    <script src="{{asset('admin')}}/modules/bootstrap/js/bootstrap.min.js"></script>
    <script src="{{asset('admin')}}/modules/nicescroll/jquery.nicescroll.min.js"></script>
    <script src="{{asset('admin')}}/modules/moment.min.js"></script>
    <script src="{{asset('admin')}}/js/stisla.js"></script>

    <!-- JS Libraies -->

    <script src="{{asset('admin')}}/modules/datatables/datatables.min.js"></script>
    <script src="{{asset('admin')}}/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js"></script>
    <script src="{{asset('admin')}}/modules/sweetalert/sweetalert.min.js"></script>
    <script src="{{asset('admin')}}/js/scripts.js"></script>
    <script src="{{asset('admin')}}/js/custom.js"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    <!-- Page Specific JS File -->
    @stack('js')

    <!-- Template JS File -->
    
</body>

</html>