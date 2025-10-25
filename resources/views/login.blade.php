<!DOCTYPE html>
<html>
<head>
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

        /* NAVBAR STYLE */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background-color: #ffff;
            color: white;
            padding: 10px 40px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            color: black;
            font-weight: 600;
            font-size: 1rem;
            text-decoration: none;
        }

        .navbar-brand img {
            height: 40px;
            margin-right: 10px;
        }

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

        .left-section h1 {
            font-size: 2.2rem;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .left-section p {
            color: #6c757d;
            font-size: 1rem;
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
            margin-top: 70px; /* space for navbar */
        }

        .login-box {
            width: 100%;
            max-width: 360px;
        }

        .login-box h4 {
            font-weight: 600;
            margin-bottom: 5px;
        }

        .login-box small {
            color: #6c757d;
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

        .btn-outline {
            border: 1px solid #004e89;
            color: #004e89;
            border-radius: 8px;
            padding: 10px;
            font-weight: 500;
        }

        .btn-outline:hover {
            background-color: #004e89;
            color: white;
        }

        .form-label {
            font-weight: 500;
            margin-top: 10px;
        }

        .alert {
            border-radius: 10px;
        }

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
         <form method="POST" action="{{ url('/login') }}" style="margin:0;">
            @csrf
            <button type="submit" class="btn btn-outline-secondary">Data Monitoring</button>
        </form>
    </nav>

    <div class="left-section">
        <img src="{{ asset('images/bih_logo.png') }}" alt="Bali International Hospital Logo">
        <h4 class="card-title">Welcome to <strong>Data Monitoring BIH</strong></h4>
    </div>

    <div class="right-section">
        <div class="login-box text-start">
            <h4>Log in</h4>

            @if(session('success'))
                <div class="alert alert-success mt-3">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger mt-3">{{ $errors->first('login') }}</div>
            @endif

            <form method="POST" action="{{ url('/login') }}" class="mt-3">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Email / Username</label>
                    <input type="text" name="username" class="form-control" placeholder="Enter your email" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
                </div>

                <button type="submit" class="btn btn-login w-100 mt-2">
                    <i class="bi bi-envelope me-2"></i> Log in
                </button>
            </form>
            <p class="footer-text mt-3">© {{ date('Y') }} Bali International Hospital | IT Department</p>
        </div>
    </div>
</body>
</html>
