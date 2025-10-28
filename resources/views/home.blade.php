<!DOCTYPE html>
<html>
<head>
    <title>Home - Bali International Hospital</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #5f5f5fff, #eef3f9);
            font-family: 'Segoe UI', sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .navbar {
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }

        .navbar-brand span{
            color: #292828ff !important;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .btn-outline-light {
            border-color: #ffffffcc;
        }

        .welcome-section {
            text-align: center;
            padding: 80px 20px 40px;
        }

        .welcome-section h2 {
            font-weight: 600;
            color: #004e89;
        }

        .welcome-section p {
            color: #6c757d;
            font-size: 16px;
        }

        .card-menu {
            border: none;
            border-radius: 1rem;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            background: #fff;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .card-menu:hover {
            transform: translateY(-6px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        }

        .card-icon {
            font-size: 42px;
            color: #004e89;
        }

        .card-title {
            color: #004e89;
            font-weight: 600;
        }

        .footer {
            text-align: center;
            color: #999;
            font-size: 14px;
            margin-top: auto;
            padding: 30px 0;
        }

        .footer span {
            color: #004e89;
            font-weight: 500;
        }

        @media (max-width: 992px) {
            .welcome-section {
                padding: 60px 15px 20px;
            }
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="#">
            <img src="{{ asset('images/bih_logo.png') }}" alt="BIH Logo" style="height: 40px;" class="me-2">
            <span class="fw-semibold text-white">Bali International Hospital</span>
        </a>
        <div class="d-flex ms-auto">
            <form method="GET" action="{{ route('dashboard') }}"class="me-2">
                @csrf
                <button type="submit" class="btn btn-outline-primary btn-sm px-3">Home</button>
            </form>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline-danger btn-sm px-3">Logout</button>
            </form>
        </div>
    </div>
</nav>

<section class="welcome-section">
    <h2>Welcome, {{ session('user_name') ?? 'User' }}</h2>
    <p>Sales Organization: <strong>{{ session('sales_org') ?? '-' }}</strong></p>
</section>

<!-- ✅ Semua card dalam satu row -->
<div class="container mt-4">
    <div class="row justify-content-center">

        <div class="col-md-4 mb-4">
            <a href="{{ route('billing.index') }}" class="text-decoration-none">
                <div class="card card-menu p-4 text-center">
                    <div class="card-body">
                        <div class="card-icon mb-3">💰</div>
                        <h5 class="card-title">Billing Dashboard</h5>
                        <p class="text-muted small">View and manage patient billing data.</p>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-4 mb-4">
            <a href="{{ route('stock.index') }}" class="text-decoration-none">
                <div class="card card-menu p-4 text-center">
                    <div class="card-body">
                        <div class="card-icon mb-3">💊</div>
                        <h5 class="card-title">Stock</h5>
                        <p class="text-muted small">Track and monitor stock consumption.</p>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-4 mb-4">
            <a href="{{ route('rejected.index') }}" class="text-decoration-none">
                <div class="card card-menu p-4 text-center">
                    <div class="card-body">
                        <div class="card-icon mb-3">❌</div>
                        <h5 class="card-title">Rejected</h5>
                        <p class="text-muted small">Track and monitor rejected data.</p>
                    </div>
                </div>
            </a>
        </div>

    </div>
</div>

<footer class="footer">
    © {{ date('Y') }} <span>Bali International Hospital</span> — Developed by IT Department
</footer>

</body>
</html>
