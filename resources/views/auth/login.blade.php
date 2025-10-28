<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Bali International Hospital</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            background: linear-gradient(135deg, #f7f9fb, #eef3f9);
            font-family: 'Segoe UI', sans-serif;
        }

        /* Navbar */
        .navbar {
            position: fixed;
            top: 0; left: 0; right: 0;
            background-color: #fff;
            padding: 10px 40px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            color: #000;
            font-weight: 600;
            text-decoration: none;
        }

        .navbar-brand img {
            height: 40px;
            margin-right: 10px;
        }

        .btn-outline-secondary {
            border: 1px solid #004e89;
            color: #004e89;
            border-radius: 8px;
            padding: 6px 14px;
            font-weight: 500;
            transition: 0.2s;
        }

        .btn-outline-secondary:hover {
            background-color: #004e89;
            color: white;
        }

        /* Layout */
        .left-section {
            flex: 1;
            padding: 80px;
            color: #1a1a1a;
            margin-top: 70px; /* space for navbar */
        }

        .left-section img {
            height: 120px;
            margin-bottom: 30px;
        }

        .left-section h4 {
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .right-section {
            flex: 0.7;
            background: #fff;
            border-radius: 24px 0 0 24px;
            box-shadow: -5px 0 30px rgba(0, 0, 0, 0.05);
            height: 80%;
            margin-right: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 70px;
        }

        /* Form Box */
        .login-box {
            width: 100%;
            max-width: 360px;
        }

        .login-box h4 {
            font-weight: 600;
            margin-bottom: 10px;
            color: #004e89;
        }

        .form-label {
            font-weight: 500;
            margin-top: 10px;
        }

        .btn-login {
            background-color: #004e89;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 10px;
            font-weight: 500;
            transition: 0.2s;
        }

        .btn-login:hover {
            background-color: #005fa3;
        }

        .alert {
            border-radius: 10px;
        }

        .footer-text {
            font-size: 0.9rem;
            color: #6c757d;
        }

        /* Responsive */
        @media (max-width: 992px) {
            body {
                flex-direction: column;
                text-align: center;
            }

            .left-section {
                padding: 40px 20px;
                margin-top: 90px;
            }

            .right-section {
                margin: 0;
                width: 100%;
                border-radius: 0;
                box-shadow: none;
                height: auto;
                padding: 30px 0;
                margin-top: 90px;
            }
        }
    </style>
</head>
<body>

    <!-- NAVBAR -->
    <nav class="navbar">
        <a class="navbar-brand" href="#">
            <img src="{{ asset('images/bih_logo.png') }}" alt="BIH Logo">
            Bali International Hospital
        </a>

        <!-- <form method="GET" action="{{ url('/') }}" style="margin:0;">
            <button type="submit" class="btn btn-outline-secondary">Data Monitoring</button>
        </form> -->
    </nav>

    <div class="left-section">
        <img src="{{ asset('images/bih_logo.png') }}" alt="Bali International Hospital Logo">
        <h4>Welcome to <strong>Portal BIH</strong></h4>
        <p class="text-muted">Please login using your BIH account credentials.</p>
    </div>

    <div class="right-section">
        <div class="login-box text-start">
            <h4>Log In</h4>

            @if(session('success'))
                <div class="alert alert-success mt-3">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger mt-3">{{ $errors->first('login') }}</div>
            @endif

            <form method="POST" action="{{ route('login.post') }}" class="mt-3">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" placeholder="Masukkan username" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                </div>

                <button type="submit" class="btn btn-login w-100 mt-2">Masuk</button>
            </form>

            <p class="footer-text mt-3 text-center">
                © {{ date('Y') }} Bali International Hospital | IT Department
            </p>
        </div>
    </div>

</body>
</html>
