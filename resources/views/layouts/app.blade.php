<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'BIH Monitoring')</title>

    <!-- Bootstrap 5.3.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- DataTables (optional, for pages that need it) -->
    @stack('styles')

    <!-- Shadcn-inspired Design System -->
    <link href="{{ asset('css/shadcn-style.css') }}" rel="stylesheet">
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar-shadcn">
        <div class="container-shadcn flex-between">
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <img src="{{ asset('images/bih_logo.png') }}" alt="BIH Logo">
                <span>Bali International Hospital</span>
            </a>
            <div class="d-flex align-items-center flex-gap-2">
                <a href="{{ route('dashboard') }}" class="btn-shadcn btn-shadcn-ghost btn-shadcn-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                        <polyline points="9 22 9 12 15 12 15 22" />
                    </svg>
                    Home
                </a>
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn-shadcn btn-shadcn-outline btn-shadcn-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                            <polyline points="16 17 21 12 16 7" />
                            <line x1="21" x2="9" y1="12" y2="12" />
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="py-section">
        <div class="container-shadcn">
            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer-shadcn">
        <div class="container-shadcn">
            <p>© {{ date('Y') }} <a href="#">Bali International Hospital</a> — Developed by IT Department</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>

</html>