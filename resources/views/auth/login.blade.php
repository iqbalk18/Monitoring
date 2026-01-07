<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Bali International Hospital</title>
    
    <!-- Bootstrap 5.3.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Shadcn-inspired Design System -->
    <link href="{{ asset('css/shadcn-style.css') }}" rel="stylesheet">
    
    <style>
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .login-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        
        .login-wrapper {
            display: flex;
            width: 100%;
            max-width: 900px;
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            overflow: hidden;
            box-shadow: var(--shadow-lg);
        }
        
        .login-left {
            flex: 1;
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        }
        
        .login-left img {
            width: 80px;
            height: 80px;
            object-fit: contain;
            margin-bottom: 1.5rem;
        }
        
        .login-left h1 {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: var(--foreground);
        }
        
        .login-left p {
            color: var(--muted-foreground);
            font-size: 0.9375rem;
        }
        
        .login-right {
            flex: 1;
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .login-form-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .login-form-subtitle {
            color: var(--muted-foreground);
            font-size: 0.875rem;
            margin-bottom: 1.5rem;
        }
        
        .login-footer {
            text-align: center;
            padding: 1rem;
            color: var(--muted-foreground);
            font-size: 0.8125rem;
            border-top: 1px solid var(--border);
        }
        
        @media (max-width: 768px) {
            .login-wrapper {
                flex-direction: column;
            }
            
            .login-left {
                padding: 2rem;
                text-align: center;
                align-items: center;
            }
            
            .login-left img {
                width: 60px;
                height: 60px;
            }
            
            .login-left h1 {
                font-size: 1.5rem;
            }
            
            .login-right {
                padding: 2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar-shadcn">
        <div class="container-shadcn flex-between">
            <a class="navbar-brand" href="#">
                <img src="{{ asset('images/bih_logo.png') }}" alt="BIH Logo">
                <span>Bali International Hospital</span>
            </a>
        </div>
    </nav>

    <!-- Login Container -->
    <div class="login-container">
        <div class="login-wrapper">
            <!-- Left Section -->
            <div class="login-left">
                <img src="{{ asset('images/bih_logo.png') }}" alt="BIH Logo">
                <h1>Welcome to Portal BIH</h1>
                <p>Access your hospital monitoring dashboard with your credentials.</p>
            </div>
            
            <!-- Right Section - Login Form -->
            <div class="login-right">
                <h2 class="login-form-title">Sign In</h2>
                <p class="login-form-subtitle">Enter your credentials to continue</p>
                
                @if(session('success'))
                <div class="alert-shadcn alert-shadcn-success mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    <div class="alert-description">{{ session('success') }}</div>
                </div>
                @endif

                @if($errors->any())
                <div class="alert-shadcn alert-shadcn-destructive mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" x2="9" y1="9" y2="15"/><line x1="9" x2="15" y1="9" y2="15"/></svg>
                    <div class="alert-description">{{ $errors->first('login') }}</div>
                </div>
                @endif
                
                <form method="POST" action="{{ route('login.post') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="username" class="form-label-shadcn">Username</label>
                        <input type="text" name="username" id="username" class="form-control-shadcn" placeholder="Enter your username" required autofocus>
                    </div>
                    
                    <div class="mb-4">
                        <label for="password" class="form-label-shadcn">Password</label>
                        <input type="password" name="password" id="password" class="form-control-shadcn" placeholder="Enter your password" required>
                    </div>
                    
                    <button type="submit" class="btn-shadcn btn-shadcn-primary w-100" style="height: 2.75rem;" id="loginBtn">
                        <span class="btn-content">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" x2="3" y1="12" y2="12"/></svg>
                            Sign In
                        </span>
                        <span class="btn-loading" style="display: none;">
                            <svg class="spinner" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg>
                            Signing in...
                        </span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="login-footer">
        © {{ date('Y') }} Bali International Hospital — IT Department
    </div>

    <style>
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        
        .spinner {
            animation: spin 1s linear infinite;
        }
        
        #loginBtn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }
        
        .btn-content, .btn-loading {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-4px); }
            20%, 40%, 60%, 80% { transform: translateX(4px); }
        }
        
        .alert-shake {
            animation: shake 0.5s ease-in-out;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelector('form').addEventListener('submit', function(e) {
            const btn = document.getElementById('loginBtn');
            const btnContent = btn.querySelector('.btn-content');
            const btnLoading = btn.querySelector('.btn-loading');
            
            // Disable button and show spinner
            btn.disabled = true;
            btnContent.style.display = 'none';
            btnLoading.style.display = 'inline-flex';
        });
        
        // Add shake animation to error alert
        const errorAlert = document.querySelector('.alert-shadcn-destructive');
        if (errorAlert) {
            errorAlert.classList.add('alert-shake');
        }
    </script>
</body>
</html>
