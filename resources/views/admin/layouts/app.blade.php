<!DOCTYPE html>
<html lang="en">
<head>
    <title>@yield('title') || Forevory</title>
    <meta name="_token" content="{{ csrf_token() }}">
    @include('admin.layouts.header-links')
</head>

<body class="topPad">
    <div id="app">
        <!-- header -->
        @include('admin.layouts.header')
        @yield('content')

    </div>
        <!-- js links -->
        @include('admin.layouts.footer-links')
        @yield('js')
        
    </body>
</html>