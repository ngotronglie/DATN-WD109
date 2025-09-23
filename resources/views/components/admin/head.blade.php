<!DOCTYPE html>
<html lang="en">


<head>
     <!-- Title Meta -->
     <meta charset="utf-8" />
     <title>TechZone</title>
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <meta name="description" content="A fully responsive premium admin dashboard template" />
     <meta name="author" content="Techzaa" />
     <meta http-equiv="X-UA-Compatible" content="IE=edge" />

     <!-- App favicon -->
     <link rel="shortcut icon" href="{{ asset('dashboard/assets/images/favicon.ico') }}" />

     <!-- CSRF Token -->
     <meta name="csrf-token" content="{{ csrf_token() }}">

     <!-- Vendor css (Require in all Page) -->
     <link href="{{ asset('dashboard/assets/css/vendor.min.css') }}" rel="stylesheet" type="text/css" />

     <!-- Icons css (Require in all Page) -->
     <link href="{{ asset('dashboard/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />

     <!-- App css (Require in all Page) -->
     <link href="{{ asset('dashboard/assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />

     <!-- Theme Config js (Require in all Page) -->
     <script src="{{ asset('dashboard/assets/js/config.min.js') }}"></script>

     <style>
          /* Admin typography overrides */
          :root {
               --admin-font-family: sans-serif;
          }

          body {
               font-family: var(--admin-font-family);
               -webkit-font-smoothing: antialiased;
               -moz-osx-font-smoothing: grayscale;
          }

          h1, h2, h3, h4, h5, h6, .card-title, .fw-semibold {
               letter-spacing: 0.2px;
          }

          .text-muted {
               color: #6b7280 !important; /* neutral-500 */
          }
     </style>

</head>
