<!DOCTYPE html>
<html lang="en">
<head>
<title>@yield('title') || Forevory</title>
<meta name="_token" content="{{ csrf_token() }}">    
    @include('user.layouts.header-links')
    @yield('css')
</head>

<body class="topPad @if((Request::segment(1)  == 'view-profile' ) ) overflow-hidden @endif">
    <div id="app">
        <!-- header -->
        @include('user.layouts.header')
        @yield('content')

    </div>
        <!-- js links -->
        
        @include('user.layouts.footer-links')
        @include('user.layouts.footer')
        @yield('js')
    </body>
</html>