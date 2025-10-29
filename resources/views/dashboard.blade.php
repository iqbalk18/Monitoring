@extends('layouts.auth')
@section('title', 'Dashboard')

@section('body')
<nav class="navbar navbar-expand-lg">
    <div class="container-fluid px-4">
        <a class="navbar-brand d-flex align-items-center" href="#">
            <img src="{{ asset('images/bih_logo.png') }}" alt="BIH Logo">
            <span>Bali International Hospital</span>
        </a>

        <div class="d-flex align-items-center ms-auto">
            <div class="me-3 text-end">
                <span class="fw-semibold text-dark">User: {{ $user['username'] }}</span><br>
                <small class="text-muted">Role: ({{ $user['role'] }})</small>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline-danger btn-sm px-3">Logout</button>
            </form>
        </div>
    </div>
</nav>

<section class="text-center mt-5 pt-5">
    <h2 class="fw-bold text-primary">Welcome, {{ $user['username'] }}</h2>
    <p class="text-muted mb-4">You are logged in as <strong>{{ $user['role'] }}</strong>.</p>
</section>

<div class="container mt-4 pb-5">
    <div class="row justify-content-center">

        {{-- Adjustment Stock --}}
        <div class="col-md-3 mb-4">
<form method="GET" action="{{ route('showForm') }}" style="margin:0;">
                @csrf
                <button type="submit" class="border-0 bg-transparent w-100 text-decoration-none p-0">
                <div class="card border-0 shadow-sm rounded-4 h-100 p-4 text-center card-menu">
                    <div class="card-icon mb-3 fs-1">📦</div>
                    <h5 class="fw-bold text-primary">Adjusment Stock</h5>
                        <p class="text-muted small mb-0">Adjustment stock between SAP & TrakCare</p>
                    </div>
                </button>
            </form>
        </div>

        {{-- Data Monitoring --}}
        <div class="col-md-3 mb-4">
            <form method="POST" action="{{ url('/loginmdw') }}" style="margin:0;">
                @csrf
                <button type="submit" class="border-0 bg-transparent w-100 text-decoration-none p-0">
                    <div class="card border-0 shadow-sm rounded-4 h-100 p-4 text-center card-menu">
                        <div class="card-icon mb-3 fs-1">📊</div>
                        <h5 class="fw-bold text-primary">Data Monitoring</h5>
                        <p class="text-muted small mb-0">Data Monitoring between TrakCare & SAP.</p>
                    </div>
                </button>
            </form>
        </div>

        {{-- Log --}}
        <div class="col-md-3 mb-4">
            <a href="#" class="text-decoration-none">
                <div class="card border-0 shadow-sm rounded-4 h-100 p-4 text-center card-menu">
                    <div class="card-icon mb-3 fs-1">🧾</div>
                    <h5 class="fw-bold text-primary">Log</h5>
                    <p class="text-muted small mb-0">Log activity in this application.</p>
                </div>
            </a>
        </div>

        {{-- Settings (ADMIN only) --}}
        @if($user['role'] === 'ADMIN')
        <div class="col-md-3 mb-4">
            <a href="{{ route('settings') }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm rounded-4 h-100 p-4 text-center card-menu">
                    <div class="card-icon mb-3 fs-1">⚙️</div>
                    <h5 class="fw-bold text-primary">Settings</h5>
                    <p class="text-muted small mb-0">Configuration system & access user.</p>
                </div>
            </a>
        </div>
        @endif

    </div>
</div>

<footer>
    © {{ date('Y') }} <span>Bali International Hospital</span> — Developed by IT Department
</footer>

{{-- Style khusus dashboard --}}
<style>
    .card-menu {
        transition: transform 0.25s ease, box-shadow 0.25s ease;
        background: #fff;
    }

    .card-menu:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
    }
</style>
@endsection
