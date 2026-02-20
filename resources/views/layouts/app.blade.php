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
                <button id="theme-toggle" class="btn-shadcn btn-shadcn-ghost btn-shadcn-sm" aria-label="Toggle theme">
                    <!-- Sun Icon (for Dark Mode) -->
                    <svg id="theme-icon-sun" class="d-none" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <circle cx="12" cy="12" r="5"></circle>
                        <line x1="12" y1="1" x2="12" y2="3"></line>
                        <line x1="12" y1="21" x2="12" y2="23"></line>
                        <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
                        <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
                        <line x1="1" y1="12" x2="3" y2="12"></line>
                        <line x1="21" y1="12" x2="23" y2="12"></line>
                        <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
                        <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
                    </svg>
                    <!-- Moon Icon (for Light Mode) -->
                    <svg id="theme-icon-moon" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
                    </svg>
                </button>

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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const themeToggleBtn = document.getElementById('theme-toggle');
            const themeIconSun = document.getElementById('theme-icon-sun');
            const themeIconMoon = document.getElementById('theme-icon-moon');
            const htmlElement = document.documentElement;

            // Check for saved theme preference or use system preference
            const savedTheme = localStorage.getItem('theme');
            const systemTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            const currentTheme = savedTheme || systemTheme;

            // Apply the initial theme
            if (currentTheme === 'dark') {
                htmlElement.classList.add('dark');
                themeIconSun.classList.remove('d-none');
                themeIconMoon.classList.add('d-none');
            } else {
                htmlElement.classList.remove('dark');
                themeIconSun.classList.add('d-none');
                themeIconMoon.classList.remove('d-none');
            }

            // Toggle theme on click
            themeToggleBtn.addEventListener('click', function () {
                if (htmlElement.classList.contains('dark')) {
                    htmlElement.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                    themeIconSun.classList.add('d-none');
                    themeIconMoon.classList.remove('d-none');
                } else {
                    htmlElement.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                    themeIconSun.classList.remove('d-none');
                    themeIconMoon.classList.add('d-none');
                }
            });
        });
    </script>

    @stack('scripts')
</body>

</html>