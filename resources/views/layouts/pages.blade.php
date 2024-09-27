<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>SIH3 BWS Sumatra VI</title>
    <link rel="icon" href="{{asset('icon.ico')}}">
    <link rel="stylesheet" href="{{asset('pages/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    @stack('css')
    <style>
        .navbar {
            background-color: rgb(10, 71, 147)
        }

        .navbar .nav-link {
            color: white !important;
            font-weight: 400 !important;
            font-size: 16px !important; 
        }

        .img-logo {
            width: 25%;
        }

        @media only screen and (max-width: 820px) {
            .navbar .nav-link {
                color: white !important;
            }

            .img-logo {
                width: 50% !important;
            }

            #clock {
                display: none
            }
        }
    </style>
</head>
<body onload="createCaptcha()">
    @include('components.pages.header')
    
    @include('components.pages.navbar')

    <div class="container-fluid mt-4">
        @yield('content')
    </div>

    <footer class="mt-3 mb-3 text-center">
        <h6>&copy;{{date('Y')}} BALAI WILAYAH SUNGAI SUMATERA VI</h6>
    </footer>
    @stack('modal')
    <script src="{{asset('pages/js/jquery.min.js')}}"></script>
    <script src="{{asset('pages/js/bootstrap.bundle.min.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.full.min.js"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @stack('js')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
</body> 
</html>