@extends('layouts.auth')
@section('title', 'Margin')

@section('body')
<nav class="navbar navbar-expand-lg">
    <div class="container-fluid px-4">
        <a class="navbar-brand d-flex align-items-center" href="{{ route('dashboard') }}">
            <img src="{{ asset('images/bih_logo.png') }}" alt="BIH Logo">
            <span>Bali International Hospital</span>
        </a>

        <div class="d-flex align-items-center ms-auto">
            <div class="me-3 text-end">
                <span class="fw-semibold text-dark">Margin</span><br>
                <small class="text-muted">Master Data Management</small>
            </div>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-primary btn-sm px-3">Kembali</a>
        </div>
    </div>
</nav>

<div class="container mt-4 pb-5">
    {{-- Alert Messages --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- Header & Actions --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h4 class="fw-bold text-primary mb-0">📋 Data Margin</h4>
                    <p class="text-muted small mb-0">Manage margin data</p>
                </div>
                <div class="col-md-6 text-end">
                    <a href="{{ route('margin.create') }}" class="btn btn-primary">
                        ➕ Tambah Data
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Search Form --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <form method="GET" action="{{ route('margin.index') }}">
                <div class="row">
                    <div class="col-md-10">
                        <input type="text" 
                               name="search" 
                               class="form-control" 
                               placeholder="Cari berdasarkan Code, Description, Margin, ARCIM_ServMateria..."
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">🔍 Cari</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Data Table --}}
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-primary">
                        <tr>
                            <th class="px-4 py-3">No</th>
                            <th class="py-3">TypeofItem Code</th>
                            <th class="py-3">TypeofItem Description</th>
                            <th class="py-3">Margin (%)</th>
                            <th class="py-3">ARCIM_ServMateria</th>
                            <th class="py-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($margins as $index => $margin)
                        <tr>
                            <td class="px-4 py-3">{{ $margins->firstItem() + $index }}</td>
                            <td class="py-3">{{ $margin->TypeofItemCode ?? '-' }}</td>
                            <td class="py-3">{{ Str::limit($margin->TypeofItemDesc, 40) ?? '-' }}</td>
                            <td class="py-3">
                                @if($margin->Margin !== null)
                                    <span class="badge bg-success">{{ number_format($margin->Margin) }}%</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="py-3">{{ $margin->ARCIM_ServMateria ?? '-' }}</td>
                            <td class="py-3 text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('margin.edit', $margin->id) }}"
                                        class="btn btn-success btn-sm px-3"
                                        title="Edit">
                                        Edit
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <div class="fs-1 mb-2">📭</div>
                                <p class="mb-0">Tidak ada data yang ditemukan</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        {{-- Pagination --}}
        @if($margins->hasPages())
        <div class="card-footer bg-white border-0 p-4">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    Menampilkan {{ $margins->firstItem() }} - {{ $margins->lastItem() }} dari {{ $margins->total() }} data
                </div>
                <div>
                    {{ $margins->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<footer>
    © {{ date('Y') }} <span>Bali International Hospital</span> — Developed by IT Department
</footer>

<style>
    .table th {
        font-weight: 600;
        color: #004e89;
        border-bottom: 2px solid #dee2e6;
    }
    
    .table tbody tr {
        transition: background-color 0.2s;
    }
    
    .table tbody tr:hover {
        background-color: #f8f9fa;
    }

    .btn-group-sm .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
</style>
@endsection

