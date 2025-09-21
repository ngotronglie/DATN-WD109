<!DOCTYPE html>
<html lang="en">
<head>
    @include('components.client.head')
    <style>
        /* Reset nhẹ */
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f9f9f9;
            color: #333;
        }

        /* Wrapper full chiều cao */
        .wrapper {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Header & Footer */
        header, footer {
            background: #fff;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
            z-index: 10;
        }

        /* Main */
        main {
            flex-grow: 1;
            padding: 20px 0; /* tạo khoảng cách giữa header/footer */
        }

        /* Container mặc định */
        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
        }
        /* Main */
main {
    flex-grow: 1;
    padding: 0; /* bỏ khoảng cách mặc định */
}

/* Nếu muốn các section tự tạo khoảng cách thì thêm */
section {
    margin-bottom: 40px; /* hoặc giá trị bạn muốn */
}

    </style>
</head>
<body class="d-flex flex-column min-vh-100">
    <!-- Wrapper -->
    <div class="wrapper d-flex flex-column flex-grow-1">
        
        <!-- Header -->
        @include('components.client.header')

        <!-- Main Content -->
        <main class="flex-grow-1">
            <div class="container">
                @yield('content')
            </div>
        </main>

        <!-- Footer -->
        @include('components.client.footer')

    </div>
    <!-- End Wrapper -->

    <!-- Scripts -->
    @include('components.client.script')
    @yield('script-client')
</body>
</html>
