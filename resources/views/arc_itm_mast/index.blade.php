@extends('layouts.auth')
@section('title', 'ARC Item Master')

@section('body')
<nav class="navbar navbar-expand-lg">
    <div class="container-fluid px-4">
        <a class="navbar-brand d-flex align-items-center" href="{{ route('dashboard') }}">
            <img src="{{ asset('images/bih_logo.png') }}" alt="BIH Logo">
            <span>Bali International Hospital</span>
        </a>

        <div class="d-flex align-items-center ms-auto">
            <div class="me-3 text-end">
                <span class="fw-semibold text-dark">ARC Item Master</span><br>
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
                <div class="col-md-8">
                    <h4 class="fw-bold text-primary mb-0">📋 Data ARC Item Master</h4>
                    <p class="text-muted small mb-0">Manage master data item</p>
                </div>
                <div class="col-md-4 text-end">
                    <a href="{{ route('margin.index') }}" class="btn btn-primary text-white px-4">
                        📊 Kelola Margin
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Search Form --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <form method="GET" action="{{ route('arc-itm-mast.index') }}">
                <div class="row g-3">
                    <div class="col-md-8">
                        <input type="text"
                            name="search"
                            class="form-control"
                            placeholder="Cari berdasarkan Code, Description, Category..."
                            value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="non_active" {{ request('status') == 'non_active' ? 'selected' : '' }}>Non Active</option>
                        </select>
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
                            <th class="py-3">ARCIM Code</th>
                            <th class="py-3">ARCIM Description</th>
                            <th class="py-3">Serv/Material</th>

                            <!-- Removed: ARCIC Code -->
                            <th class="py-3">ARCIC Description</th>

                            <!-- Removed: ORCAT Code -->
                            <th class="py-3">ORCAT Description</th>

                            <!-- Removed: ARCSG Code -->
                            <th class="py-3">ARCSG Description</th>

                            <!-- Removed: ARCBG Code -->
                            <th class="py-3">ARCBG Description</th>

                            <th class="py-3">Order On Its Own</th>
                            <th class="py-3">Reorder On Its Own</th>

                            <th class="py-3">Eff Date</th>
                            <th class="py-3">Eff Date To</th>
                            <th class="py-3">Status</th>

                            <th class="py-3 text-center">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($items as $index => $item)
                        <tr>
                            <td class="px-4 py-3">{{ $items->firstItem() + $index }}</td>

                            <td class="py-3">{{ $item->ARCIM_Code ?? '-' }}</td>
                            <td class="py-3">{{ Str::limit($item->ARCIM_Desc, 40) ?? '-' }}</td>
                            <td class="py-3">{{ $item->ARCIM_ServMaterial ?? '-' }}</td>

                            <!-- Only description -->
                            <td class="py-3">{{ Str::limit($item->ARCIC_Desc, 30) ?? '-' }}</td>
                            <td class="py-3">{{ Str::limit($item->ORCAT_Desc, 30) ?? '-' }}</td>
                            <td class="py-3">{{ Str::limit($item->ARCSG_Desc, 30) ?? '-' }}</td>
                            <td class="py-3">{{ Str::limit($item->ARCBG_Desc, 30) ?? '-' }}</td>

                            <td class="py-3">{{ $item->ARCIM_OrderOnItsOwn ?? '-' }}</td>
                            <td class="py-3">{{ $item->ARCIM_ReorderOnItsOwn ?? '-' }}</td>

                            <td class="py-3">{{ $item->ARCIM_EffDate ? $item->ARCIM_EffDate->format('d/m/Y') : '-' }}</td>
                            <td class="py-3">{{ $item->ARCIM_EffDateTo ? $item->ARCIM_EffDateTo->format('d/m/Y') : '-' }}</td>
                            <td class="py-3">
                                @php
                                    $today = now()->startOfDay();
                                    $isActive = is_null($item->ARCIM_EffDateTo) || $item->ARCIM_EffDateTo >= $today;
                                @endphp
                                @if($isActive)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Non Active</span>
                                @endif
                            </td>

                            

                            <td class="py-3 text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('arc-itm-mast.edit', $item->id) }}"
                                        class="btn btn-success btn-sm px-3"
                                        title="Edit">
                                        Edit
                                    </a>
                                    <a href="{{ route('arc-item-price-italy.manage', $item->ARCIM_Code) }}"
                                        class="btn btn-info btn-sm px-3"
                                        title="Manage Price">
                                        💰 Add Price
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="15" class="text-center py-5 text-muted">
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
        @if($items->hasPages())
        <div class="card-footer ">
            {{ $items->links() }}
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