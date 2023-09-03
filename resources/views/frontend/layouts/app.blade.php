<!DOCTYPE html>
<html class="no-js" lang="en_AU" />


<head>
    <meta http-equiv="Content-Type ngrok-skip-browser-warning" content="text/html; charset=UTF-8" />
    <title>@yield('title')</title>
    <meta name="description" content="" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1, shrink-to-fit=no, maximum-scale=1, user-scalable=no" />


    <link rel="stylesheet" type="text/css" href="{{ asset('frontend-assets/css/slick.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend-assets/css/slick-theme.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend-assets/css/video-js.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend-assets/css/style.css') }}" />

    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('midtrans.clientKey') }}"></script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;500&family=Raleway:ital,wght@0,400;0,600;0,800;1,200&family=Roboto+Condensed:wght@400;700&family=Roboto:wght@300;400;700;900&display=swap">

    <link rel="stylesheet" href="">

    <!-- Fav Icon -->
    <link rel="shortcut icon" type="image/x-icon" href="#" />

    <meta name="csrf-token" content="{{ csrf_token() }}" />
</head>

<body data-instant-intensity="mousedown">

    <div class="bg-light top-header justify-content-center align-items-center ">
        <div class="container">
            <div class="row align-items-center py-3 d-none d-lg-flex justify-content-between">
                <div class="col-lg-4 logo">
                    <a href="index.php" class="text-decoration-none">
                        <span class="h1 text-uppercase text-primary bg-dark px-2">Arseno</span>
                        <span class="h1 text-uppercase text-dark bg-primary px-2 ml-n1">SHOP</span>
                    </a>
                </div>
                @if (Auth::check() == true)
                    <div class="col-lg-4 d-flex justify-content-end align-items-center">
                        <ul class="  align-items-center">
                            <li>
                                <p class="text-dark">{{ Auth::user()->name }}</p>
                            </li>
                            <li>
                                <a href="#" class="text-danger ml-auto">
                                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                </a>
                            </li>
                        </ul>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <header class="bg-dark">
        <div class="container">
            <nav class="navbar navbar-expand-xl" id="navbar">
                <div class="right-nav py-3 d-flex align-items-center">
                    <a href="{{ route('costumer.chart') }}" class="ml-3 d-flex pt-2">
                        <i class="fas fa-shopping-cart text-primary"></i>
                    </a>
                </div>
            </nav>
        </div>
    </header>

    @yield('content')

    <script src="{{ asset('frontend-assets/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('frontend-assets/js/bootstrap.bundle.5.1.3.min.js') }}"></script>
    <script src="{{ asset('frontend-assets/js/instantpages.5.1.0.min.js') }}"></script>
    <script src="{{ asset('frontend-assets/js/lazyload.17.6.0.min.js') }}"></script>
    <script src="{{ asset('frontend-assets/js/slick.min.js') }}"></script>
    <script src="{{ asset('frontend-assets/js/custom.js') }}"></script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    @yield('costumeJS')

</body>

</html>
