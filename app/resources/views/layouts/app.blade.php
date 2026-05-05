<!DOCTYPE html>
<html lang="en">
    @include('includes/header-scripts')
        @yield('cascadingstyle')
    <body class="g-sidenav-show  bg-gray-100">        
            @yield('content') 
        @include('includes/footer-script')
        @yield('javascript')
    </body>
</html>