<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ 'Abbott Laboratories' }}</title>

    <!-- Scripts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('js/bootstrap.min.js') }}" defer></script>
    <script src="{{ asset('js/sb-admin-2.min.js') }}" defer></script>
    
    

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap-grid.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/sb-admin-2.min.css')}}">

    <!--Icons-->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/brands.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/solid.min.css')}}">

    <!--Modal-->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/modal.css')}}">

    <!-- sweet alert-->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>


    <!--FOTNAWESOME-->
    <script src="https://kit.fontawesome.com/df3297aae9.js"></script>

    <!--TINYMCE-->
    <script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
    
</head>
<body id="page-top">
    <div id="wrapper" style="height: 100% !important;">
            @include('includes.sidebar_left')
        
        <div id="content-wrapper" class="d-flex flex-column">
            @include('includes.nav')
            @yield('content')
           
        </div>
        @include('includes.footer')
        
    </div>
</body>
</html>
