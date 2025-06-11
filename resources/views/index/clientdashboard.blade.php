@include('components.client.head')

<body>
    <!--[if lt IE 8]>
    <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->

    <!-- Body main wrapper start -->
    <div class="wrapper">
        @include('components.client.header')
        
        @include('components.client.slider')

        @include('components.client.content')

        @include('components.client.footer')
    </div>
    <!-- Body main wrapper end -->

    @include('components.client.script')
</body>
</html>
