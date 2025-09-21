@include('components.admin.head')

<body>

     <!-- START Wrapper -->
     <div class="wrapper">

          <!-- ========== Topbar Start ========== -->
        @include('components.admin.header')

        @include('components.admin.rightsidebar')

        @include('components.admin.menu')
          <!-- ==================================================== -->
          <!-- Start right Content here -->
          <!-- ==================================================== -->
          <div class="page-content">

            @yield('content')

            @include('components.admin.footer')

          </div>
          <!-- ==================================================== -->
          <!-- End Page Content -->
          <!-- ==================================================== -->

     </div>
     <!-- END Wrapper -->

       @include('components.admin.script')
       @yield('script')

</body>
</html>
