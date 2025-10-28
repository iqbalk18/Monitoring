<!DOCTYPE html>
<html>
<head>
    <title>@yield('title') - Bali International Hospital</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            margin: 0;
            background: linear-gradient(135deg, #f7f9fb, #eef3f9);
            font-family: 'Segoe UI', sans-serif;
            display: flex;
            flex-direction: column;
        }

        /* Navbar global (agar bisa dipakai semua halaman) */
        .navbar {
            background-color: #fff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            padding: 12px 40px;
            z-index: 1000;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            font-weight: 600;
            color: #004e89 !important;
        }

        .navbar-brand img {
            height: 40px;
            margin-right: 10px;
        }

        footer {
            text-align: center;
            color: #999;
            font-size: 14px;
            margin-top: auto;
            padding: 25px 0;
            background: #fff;
            border-top: 1px solid #eaeaea;
        }

        footer span {
            color: #004e89;
            font-weight: 500;
        }
    </style>
</head>
<body>

    @yield('body')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
