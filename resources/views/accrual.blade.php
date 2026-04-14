@extends('layouts.app')

@section('title', 'Accrual - Bali International Hospital')

@push('styles')
    <style>
        .container-shadcn {
            max-width: 95vw !important;
        }
    </style>
@endpush

@section('content')
    <!-- Page Header -->
    <div class="flex-between mb-4" style="flex-wrap: wrap; gap: 1rem;">
        <div>
            <h2 class="section-title">Accrual Monitoring</h2>
            <p class="section-desc">User: <strong>{{ $user }}</strong> | Sales Org: <strong>{{ $org }}</strong></p>
        </div>
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div class="alert-shadcn alert-shadcn-success mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                <polyline points="22 4 12 14.01 9 11.01" />
            </svg>
            <div class="alert-description">{{ session('success') }}</div>
        </div>
    @endif
    @if(isset($error) || $errors->any())
        <div class="alert-shadcn alert-shadcn-destructive mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10" />
                <line x1="15" x2="9" y1="9" y2="15" />
                <line x1="9" x2="15" y1="9" y2="15" />
            </svg>
            <div class="alert-description">
                @if(isset($error))
                    {{ $error }}
                @endif
                @if($errors->any())
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    @endif

    <!-- Filter Form -->
    <div class="card-shadcn mb-4">
        <div class="card-shadcn-body">
            <form method="GET">
                <div class="row g-3 align-items-end">
                    <!-- Date Range -->
                    <div class="col-md-2">
                        <label class="form-label-shadcn">From</label>
                        <input type="date" name="fromDate" class="form-control-shadcn" value="{{ $fromDate }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label-shadcn">To</label>
                        <input type="date" name="toDate" class="form-control-shadcn" value="{{ $toDate }}">
                    </div>

                    <!-- Status -->
                    <div class="col-md-2">
                        <label class="form-label-shadcn">Status</label>
                        <select name="status" class="form-control-shadcn">
                            <option value="success" {{ $status === 'success' ? 'selected' : '' }}>Success</option>
                            <option value="failed" {{ $status === 'failed' ? 'selected' : '' }}>Failed</option>
                            <option value="all" {{ $status === 'all' ? 'selected' : '' }}>All</option>
                        </select>
                    </div>

                    <!-- Recap Code -->
                    <div class="col-md-3">
                        <label class="form-label-shadcn">Recap Code</label>
                        <input type="text" name="recapCode" class="form-control-shadcn" value="{{ $recapCode ?? '' }}"
                            placeholder="Enter Recap Code for detail...">
                    </div>

                    <!-- Filter Button -->
                    <div class="col-md-3">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn-shadcn btn-shadcn-primary flex-grow-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3" />
                                </svg>
                                Filter
                            </button>
                            @if($recapCode)
                                <a href="{{ route('accrual.index') }}" class="btn-shadcn btn-shadcn-outline">Clear</a>
                            @endif
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary (if recapCode) -->
    @if($recapCode && $recaps->isNotEmpty())
        @php $recap = $recaps->first(); @endphp
        <div class="card-shadcn mb-4 border-l-4" style="border-left-color: var(--brand);">
            <div class="card-shadcn-body">
                <div class="row">
                    <div class="col-md-3">
                        <small class="text-muted d-block mb-1 text-uppercase fw-bold">Recap Code</small>
                        <p class="mb-0 fw-semibold">{{ $recap['recapCode'] }}</p>
                    </div>
                    <div class="col-md-2">
                        <small class="text-muted d-block mb-1 text-uppercase fw-bold">SAP SO</small>
                        <p class="mb-0">{{ $recap['sapSoNumber'] ?? '-' }}</p>
                    </div>
                    <div class="col-md-2">
                        <small class="text-muted d-block mb-1 text-uppercase fw-bold">Status</small>
                        <span class="badge-shadcn {{ strtolower($recap['status']) === 'success' ? 'badge-shadcn-success' : 'badge-shadcn-secondary' }}">
                            {{ $recap['status'] }}
                        </span>
                    </div>
                    <div class="col-md-2">
                        <small class="text-muted d-block mb-1 text-uppercase fw-bold">Period</small>
                        <p class="mb-0">{{ $recap['accrualPeriod'] ?? '-' }}</p>
                    </div>
                    <div class="col-md-3 text-end">
                        <small class="text-muted d-block mb-1 text-uppercase fw-bold">Total Final Amount</small>
                        <p class="mb-0 fs-5 fw-bold text-brand">Rp{{ number_format($recap['totalFinalAmount'] ?? 0, 0, ',', '.') }}</p>
                    </div>
                    <div class="col-md-2 text-end">
                        <small class="text-muted d-block mb-1 text-uppercase fw-bold">Amount Free</small>
                        <p class="mb-0 fw-semibold">Rp{{ number_format($recap['totalAmountFree'] ?? 0, 0, ',', '.') }}</p>
                    </div>
                    <div class="col-md-2 text-end">
                        <small class="text-muted d-block mb-1 text-uppercase fw-bold">Total Amount</small>
                        <p class="mb-0 fw-semibold">Rp{{ number_format($recap['totalAmount'] ?? 0, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Data Table -->
    <div class="card-shadcn">
        <div class="card-shadcn-header flex-between">
            <h3 class="card-shadcn-title">Accrual List</h3>
            <div class="d-flex align-items-center gap-3">
                <span class="badge-shadcn badge-shadcn-secondary">{{ $totalData ?? $recaps->count() }} total records</span>
                <a href="{{ route('accrual.export') }}?{{ http_build_query([
                    'fromDate' => $fromDate,
                    'toDate' => $toDate,
                    'status' => $status,
                    'recapCode' => $recapCode ?? null,
                ]) }}" class="btn-shadcn btn-shadcn-success btn-shadcn-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                        <polyline points="7 10 12 15 17 10" />
                        <line x1="12" x2="12" y1="15" y2="3" />
                    </svg>
                    Export Excel
                </a>
            </div>
        </div>
        <div class="card-shadcn-body p-0">
            <div style="overflow-x: auto;">
                <table class="table-shadcn" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Recap Code</th>
                            <th>SAP SO Number</th>
                            <th>Accrual Type</th>
                            <th>Period</th>
                            <th>Document Date</th>
                            <th style="text-align: right;">Final Amount</th>
                            <th style="text-align: right;">Amount Free</th>
                            <th style="text-align: right;">Total Amount</th>
                            <th>Status</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recaps as $recap)
                            <tr>
                                <td class="fw-semibold text-brand">{{ $recap['recapCode'] }}</td>
                                <td style="font-family: monospace;">{{ $recap['sapSoNumber'] ?? '-' }}</td>
                                <td>{{ $recap['accrualType'] ?? '-' }}</td>
                                <td>{{ $recap['accrualPeriod'] ?? '-' }}</td>
                                <td>{{ $recap['documentDate'] ?? '-' }}</td>
                                <td style="text-align: right;">Rp{{ number_format($recap['totalFinalAmount'] ?? 0, 0, ',', '.') }}</td>
                                <td style="text-align: right;">Rp{{ number_format($recap['totalAmountFree'] ?? 0, 0, ',', '.') }}</td>
                                <td style="text-align: right;">Rp{{ number_format($recap['totalAmount'] ?? 0, 0, ',', '.') }}</td>
                                <td>
                                    <span class="badge-shadcn {{ strtolower($recap['status']) === 'success' ? 'badge-shadcn-success' : 'badge-shadcn-secondary' }}">
                                        {{ $recap['status'] }}
                                    </span>
                                </td>
                                <td style="font-size: 0.8125rem;">{{ $recap['createdAt'] ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center text-muted" style="padding: 3rem;">No accrual data found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if(!$recapCode)
        <div class="card-shadcn-footer">
            <div class="flex-between">
                <a href="{{ request()->fullUrlWithQuery(['page' => max(($currentPage ?? 1) - 1, 1)]) }}"
                    class="btn-shadcn btn-shadcn-outline btn-shadcn-sm {{ ($currentPage ?? 1) <= 1 ? 'disabled' : '' }}"
                    style="{{ ($currentPage ?? 1) <= 1 ? 'opacity: 0.5; pointer-events: none;' : '' }}">
                    ← Prev
                </a>
                <span class="text-muted" style="font-size: 0.875rem;">Page {{ $currentPage ?? 1 }} of
                    {{ $lastPage ?? 1 }}</span>
                <a href="{{ request()->fullUrlWithQuery(['page' => min(($currentPage ?? 1) + 1, $lastPage ?? 1)]) }}"
                    class="btn-shadcn btn-shadcn-outline btn-shadcn-sm {{ ($currentPage ?? 1) >= ($lastPage ?? 1) ? 'disabled' : '' }}"
                    style="{{ ($currentPage ?? 1) >= ($lastPage ?? 1) ? 'opacity: 0.5; pointer-events: none;' : '' }}">
                    Next →
                </a>
            </div>
        </div>
        @endif
    </div>
@endsection
