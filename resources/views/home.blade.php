@extends('layouts.app')

@section('title', 'Home - Bali International Hospital')

@section('content')
@php
    $user = $user ?? session('user');
@endphp
<style>
    .home-header {
        text-align: center;
        margin-bottom: 2.5rem;
    }

    .home-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--foreground);
        margin-bottom: 0.5rem;
    }

    .home-subtitle {
        font-size: 0.9375rem;
        color: var(--muted-foreground);
    }

    .home-subtitle strong {
        color: var(--foreground);
        font-weight: 600;
    }

    /* Dashboard Cards Grid */
    .menu-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.25rem;
        max-width: 1000px;
        margin: 0 auto;
    }

    @media (max-width: 992px) {
        .menu-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 576px) {
        .menu-grid {
            grid-template-columns: 1fr;
        }
    }

    .menu-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        padding: 1.75rem 1.25rem;
        text-decoration: none;
        color: inherit;
        transition: all 0.2s ease;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    .menu-card:hover {
        border-color: var(--ring);
        box-shadow: var(--shadow-lg);
        transform: translateY(-4px);
        color: inherit;
    }

    .menu-card-icon {
        width: 64px;
        height: 64px;
        border-radius: var(--radius);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        margin-bottom: 1rem;
        transition: transform 0.2s ease;
    }

    .menu-card:hover .menu-card-icon {
        transform: scale(1.1);
    }

    .menu-card-icon-billing {
        background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
    }

    .menu-card-icon-stock {
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
    }

    .menu-card-icon-rejected {
        background: linear-gradient(135deg, #fef2f2 0%, #fecaca 100%);
    }

    .menu-card-icon-management {
        background: linear-gradient(135deg, #f3e8ff 0%, #e9d5ff 100%);
    }

    .menu-card-title {
        font-size: 0.9375rem;
        font-weight: 600;
        color: var(--foreground);
        margin-bottom: 0.25rem;
    }

    .menu-card-desc {
        font-size: 0.8125rem;
        color: var(--muted-foreground);
        margin: 0;
        line-height: 1.4;
    }
</style>

<!-- Header -->
<div class="home-header">
    <h1 class="home-title">Welcome, {{ session('user_name') ?? 'User' }}</h1>
    <p class="home-subtitle">Sales Organization: <strong>{{ session('sales_org') ?? 'BIH00' }}</strong></p>
</div>

<!-- Menu Cards -->
<div class="menu-grid">
    <!-- Billing Dashboard -->
    @if(user_can_data_monitoring($user, 'data_monitoring_billing'))
        <a href="{{ route('billing.index') }}" class="menu-card">
            <div class="menu-card-icon menu-card-icon-billing">💰</div>
            <h3 class="menu-card-title">Billing Dashboard</h3>
            <p class="menu-card-desc">View patient billing data</p>
        </a>
    @endif

    <!-- Stock -->
    @if(user_can_data_monitoring($user, 'data_monitoring_stock'))
    <a href="{{ route('stock.index') }}" class="menu-card">
        <div class="menu-card-icon menu-card-icon-stock">📦</div>
        <h3 class="menu-card-title">Stock</h3>
        <p class="menu-card-desc">Monitor stock consumption</p>
    </a>
    @endif

    <!-- Rejected -->
    @if(user_can_data_monitoring($user, 'data_monitoring_rejected'))
    <a href="{{ route('rejected.index') }}" class="menu-card">
        <div class="menu-card-icon menu-card-icon-rejected">❌</div>
        <h3 class="menu-card-title">Rejected</h3>
        <p class="menu-card-desc">Track rejected data</p>
    </a>
    @endif

    <!-- Stock Management -->
    {{-- @if(user_can_data_monitoring($user, 'data_monitoring_stock_management'))
    <a href="{{ route('stock-management.index') }}" class="menu-card">
        <div class="menu-card-icon menu-card-icon-management">🗃️</div>
        <h3 class="menu-card-title">Stock Management</h3>
        <p class="menu-card-desc">Manage imports & calculations</p>
    </a>
    @endif --}}
</div>
@endsection
