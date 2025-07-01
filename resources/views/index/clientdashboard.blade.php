<!DOCTYPE html>
<html lang="en">
@include('components.client.head')

<body>
    <!-- Body main wrapper start -->
    <div class="wrapper">
        <!-- START HEADER AREA -->
        @include('components.client.header')
        <!-- END HEADER AREA -->

        @yield('content')

        <!-- START FOOTER AREA -->
        @include('components.client.footer')
        <!-- END FOOTER AREA -->
    </div>
        <!-- Body main wrapper end -->

        <!-- Placed JS at the end of the document so the pages load faster -->
        @include('components.client.script')

        @yield('script-client')
</body>
</html>
