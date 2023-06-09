<head>
    <meta charset="utf-8">

    <title>
        @yield('title')
    </title>

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no, minimal-ui">

    <!-- Call App Mode on ios devices -->
    <meta name="apple-mobile-web-app-capable" content="yes"/>

    <!-- Remove Tap Highlight on Windows Phone IE -->
    <meta name="msapplication-tap-highlight" content="no">

    <!-- base css -->
    <link id="vendorsbundle" rel="stylesheet" type="text/css" media="screen, print" href="{{ asset("$asset_template/css/vendors.bundle.css") }}">
    <link id="appbundle" rel="stylesheet" type="text/css" media="screen, print" href="{{ asset("$asset_template/css/app.bundle.css") }}">
    <link id="mytheme" rel="stylesheet" media="screen, print" href="{{ asset("$asset_template/css/themes/cust-theme-8.css") }}">
    <link id="myskin" rel="stylesheet" type="text/css" media="screen, print" href="{{ asset("$asset_template/css/skins/skin-master.css") }}">
    <link rel="stylesheet" type="text/css" media="screen, print" href="{{ asset("$asset_template/css/fa-brands.css") }}">
    <link rel="stylesheet" type="text/css" media="screen, print" href="{{ asset("$asset_template/css/fa-light.css") }}">
    <link rel="stylesheet" type="text/css" media="screen, print" href="{{ asset("$asset_template/css/fa-regular.css") }}">
    <link rel="stylesheet" type="text/css" media="screen, print" href="{{ asset("$asset_template/css/fa-solid.css") }}">
    <link rel="stylesheet" type="text/css" media="screen, print" href="{{ asset("$asset_template/css/notifications/sweetalert2/sweetalert2.bundle.css") }}">

    <!-- Plugins -->
    <link rel="stylesheet" type="text/css" media="screen, print" href="{{ asset("$asset_template/css/formplugins/select2/select2.bundle.css") }}">
    <link rel="stylesheet" type="text/css" media="screen, print" href="{{ asset("$asset_template/css/formplugins/bootstrap-datepicker/bootstrap-datepicker.css") }}">
    <link rel="stylesheet" type="text/css" media="screen, print" href="{{ asset("$asset_template/css/formplugins/bootstrap-daterangepicker/bootstrap-daterangepicker.css") }}">

    <!-- Favicon-->
    <link rel="icon" href="{{ asset('img/favicon.ico') }}">

    <!-- You can add your own stylesheet here to override any styles that comes before it -->

    <link rel="stylesheet" type="text/css" media="screen, print" href='{{ asset("css/app.css") }}'/>
    @stack('css')

    @stack('stylesheet')

    @livewireStyles
</head>