<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Shop TechZone</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    <link rel="shortcut icon" type="{{ asset('frontend/image/x-icon') }}"
        href="{{ asset('frontend/img/icon/favicon.png') }}">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- All CSS Files -->
    <!-- Bootstrap fremwork main css -->
    <link rel="stylesheet" href="{{ asset('frontend/css/bootstrap.min.css') }}">
    <!-- Nivo-slider css -->
    <link rel="stylesheet" href="{{ asset('frontend/lib/css/nivo-slider.css') }}">
    <!-- This core.css file contents all plugings css file. -->
    <link rel="stylesheet" href="{{ asset('frontend/css/core.css') }}">
    <!-- Theme shortcodes/elements style -->
    <link rel="stylesheet" href="{{ asset('frontend/css/shortcode/shortcodes.css') }}">
    <!-- Theme main style -->
    <link rel="stylesheet" href="{{ asset('frontend/style.css') }}">
    <!-- Responsive css -->
    <link rel="stylesheet" href="{{ asset('frontend/css/responsive.css') }}">
    <!-- User style -->
    <link rel="stylesheet" href="{{ asset('frontend/css/custom.css') }}">

    <!-- Style customizer (Remove these two lines please) -->
    <link rel="stylesheet" href="{{ asset('frontend/css/style-customizer.css') }}">
    <link href="#" data-style="styles" rel="stylesheet">

    <!-- Modernizr JS -->
    <script src="{{ asset('frontend/js/vendor/modernizr-3.11.2.min.js') }}"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    @yield('styles')

    <style>
        /* Client typography overrides */
        :root {
            --client-font-family: sans-serif;
        }

        body {
            font-family: var(--client-font-family);
            font-size: 17px;
            color: #111;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        h1, h2, h3, h4, h5, h6 {
            letter-spacing: 0.2px;
            color: #111;
        }

        /* Emphasize product texts in black */
        .product-info h6.product-title a { color: #111; }
        .product-info .pro-price, .product-info .pro-price-sale { color: #111; }
        .product-info { font-size: 1rem; }

        /* Header navigation links in black */
        header .nav .nav-link {
            color: #111 !important;
        }
        header .nav .nav-link:hover,
        header .nav .nav-link:focus {
            color: #000 !important;
        }

        /* Use SF Pro Display for product blocks if available */
        .product-item,
        .product-info,
        .product-info h6.product-title a,
        .product-info .pro-price,
        .product-info .pro-price-sale,
        .product-views {
            font-family: "SF Pro Display", -apple-system, system-ui, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }

        /* Price styling */
        .product-info .pro-price {
            font-weight: 700;
            font-size: 2.1rem;
            color: #111;
        }
        .product-info .pro-price-sale {
            font-weight: 500;
            font-size: 2rem;
            color: #6b7280;
        }

        /* Add to cart hover: orange */
        .action-button a.add-to-cart:hover,
        .action-button a.add-to-cart:hover i,
        .action-button a[title="Add to cart"]:hover,
        .action-button a[title="Add to cart"]:hover i {
            color: #ff7a00 !important;
        }
    </style>
</head>
