@extends('layouts.app')

@section('title', 'Home - Bali International Hospital')

@section('content')
<!-- Welcome Section -->
<div class="section-header text-center mb-section">
    <h2 class="section-title">Welcome, {{ session('user_name') ?? 'User' }}</h2>
    <p class="section-desc">Sales Organization: <strong>{{ session('sales_org') ?? '-' }}</strong></p>
</div>

<!-- Dashboard Cards -->
<div class="row g-4 justify-content-center">
    <!-- Billing Dashboard -->
    <div class="col-md-4 col-sm-6">
        <a href="{{ route('billing.index') }}" class="card-dashboard">
            <div class="card-dashboard-icon">💰</div>
            <h3 class="card-dashboard-title">Billing Dashboard</h3>
            <p class="card-dashboard-desc">View and manage patient billing data.</p>
        </a>
    </div>

    <!-- Stock -->
    <div class="col-md-4 col-sm-6">
        <a href="{{ route('stock.index') }}" class="card-dashboard">
            <div class="card-dashboard-icon">📦</div>
            <h3 class="card-dashboard-title">Stock</h3>
            <p class="card-dashboard-desc">Track and monitor stock consumption.</p>
        </a>
    </div>

    <!-- Rejected -->
    <div class="col-md-4 col-sm-6">
        <a href="{{ route('rejected.index') }}" class="card-dashboard">
            <div class="card-dashboard-icon">❌</div>
            <h3 class="card-dashboard-title">Rejected</h3>
            <p class="card-dashboard-desc">Track and monitor rejected data.</p>
        </a>
    </div>

    <!-- Stock Management -->
    <div class="col-md-4 col-sm-6">
        <a href="{{ route('stock-management.index') }}" class="card-dashboard">
            <div class="card-dashboard-icon">🗃️</div>
            <h3 class="card-dashboard-title">Stock Management</h3>
            <p class="card-dashboard-desc">Manage stock imports and calculations.</p>
        </a>
    </div>
</div>
@endsection
