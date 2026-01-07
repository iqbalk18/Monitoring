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
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }

        .shake {
            animation: shake 0.5s ease-in-out;
        }

        .btn-login:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        .spinner-border-sm {
            width: 1rem;
            height: 1rem;
            border-width: 0.15em;
        }

        .alert-danger {
            background-color: #fef2f2;
            border-color: #fecaca;
            color: #991b1b;
        }

        .alert-success {
            background-color: #f0fdf4;
            border-color: #bbf7d0;
            color: #166534;
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
                <div class="alert alert-success mt-3 d-flex align-items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger mt-3 d-flex align-items-center shake" id="error-alert">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2 flex-shrink-0"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <span>{{ $errors->first('login') ?: 'Username atau password salah' }}</span>
                </div>
            @endif

            <form method="POST" action="{{ url('/login') }}" class="mt-3" id="loginForm">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Email / Username</label>
                    <input type="text" name="username" class="form-control" placeholder="Enter your email" required value="{{ old('username') }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
                </div>

                <button type="submit" class="btn btn-login w-100 mt-2" id="loginBtn">
                    <span class="btn-text">Log in</span>
                    <span class="btn-loading d-none">
                        <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                        Logging in...
                    </span>
                </button>
            </form>
            <p class="footer-text mt-3">© {{ date('Y') }} Bali International Hospital | IT Department</p>
        </div>
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const btn = document.getElementById('loginBtn');
            const btnText = btn.querySelector('.btn-text');
            const btnLoading = btn.querySelector('.btn-loading');
            
            // Disable button and show spinner
            btn.disabled = true;
            btnText.classList.add('d-none');
            btnLoading.classList.remove('d-none');
        });

        // Remove shake animation after it completes
        const errorAlert = document.getElementById('error-alert');
        if (errorAlert) {
            errorAlert.addEventListener('animationend', function() {
                this.classList.remove('shake');
            });
        }
    </script>
</body>
</html>
