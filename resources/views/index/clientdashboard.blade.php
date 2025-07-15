<!DOCTYPE html>
<html lang="en">
@include('components.client.head')

<body class="d-flex flex-column min-vh-100">
    <!-- Body main wrapper start -->
    <div class="wrapper d-flex flex-column flex-grow-1">
        <!-- START HEADER AREA -->
        @include('components.client.header')
        <!-- END HEADER AREA -->

        <main class="flex-grow-1">
            @yield('content')
        </main>

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
