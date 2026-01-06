@extends('layouts.app')

@section('title', 'Rejected - Bali International Hospital')

@section('content')
<!-- Page Header -->
<div class="flex-between mb-4" style="flex-wrap: wrap; gap: 1rem;">
    <div>
        <h2 class="section-title">Rejected Data</h2>
        <p class="section-desc">User: <strong>{{ $user }}</strong> | Sales Org: <strong>{{ $org }}</strong></p>
    </div>
</div>

<!-- Alerts -->
@if(session('success'))
<div class="alert-shadcn alert-shadcn-success mb-4">
    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
    <div class="alert-description">{{ session('success') }}</div>
</div>
@endif
@if(isset($error))
<div class="alert-shadcn alert-shadcn-destructive mb-4">
    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" x2="9" y1="9" y2="15"/><line x1="9" x2="15" y1="9" y2="15"/></svg>
    <div class="alert-description">{{ $error }}</div>
</div>
@endif

<!-- Filter Form -->
<div class="card-shadcn mb-4">
    <div class="card-shadcn-body">
        <form method="GET" action="{{ route('rejected.index') }}">
            <div class="row g-3 align-items-end">
                <!-- Data Type Dropdown -->
                <div class="col-md-2">
                    <label class="form-label-shadcn">Data Type</label>
                    <div class="dropdown" data-bs-auto-close="outside">
                        <button id="btnDataType" class="btn-shadcn btn-shadcn-outline w-100 justify-content-between" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="height: 2.5rem;">
                            Select Type
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                        </button>
                        <div class="dropdown-menu dropdown-menu-shadcn p-3" style="min-width: 200px;">
                            <div class="d-flex justify-content-between mb-2">
                                <small class="text-muted">Choose one or more</small>
                                <div>
                                    <button type="button" class="btn btn-sm btn-link p-0 me-2" onclick="selectAll('dataType')">All</button>
                                    <button type="button" class="btn btn-sm btn-link p-0 text-danger" onclick="clearAll('dataType')">Clear</button>
                                </div>
                            </div>
                            @php
                                $dataTypeOptions = ['Billing','StockConsumption','StockReturn'];
                                $selectedDataTypes = (array) ($dataType ?? $dataTypeOptions);
                            @endphp
                            @foreach($dataTypeOptions as $dt)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="dataType[]" value="{{ $dt }}" id="dt_{{ $dt }}" {{ in_array($dt, $selectedDataTypes) ? 'checked' : '' }}>
                                <label class="form-check-label" for="dt_{{ $dt }}">{{ $dt }}</label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Order Type Dropdown -->
                <div class="col-md-2">
                    <label class="form-label-shadcn">Order Type</label>
                    <div class="dropdown" data-bs-auto-close="outside">
                        <button id="btnOrderType" class="btn-shadcn btn-shadcn-outline w-100 justify-content-between" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="height: 2.5rem;">
                            Select Order
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                        </button>
                        <div class="dropdown-menu dropdown-menu-shadcn p-3" style="min-width: 200px; max-height: 250px; overflow-y: auto;">
                            <div class="d-flex justify-content-between mb-2">
                                <small class="text-muted">Choose one or more</small>
                                <div>
                                    <button type="button" class="btn btn-sm btn-link p-0 me-2" onclick="selectAll('orderType')">All</button>
                                    <button type="button" class="btn btn-sm btn-link p-0 text-danger" onclick="clearAll('orderType')">Clear</button>
                                </div>
                            </div>
                            @php
                                $orderTypeOptions = ['ZJSC','ZJME','ZJBJ','ZJBC','ZISC','ZIRE','ZIM2','ZIM1','ZIBJ','ZIBC'];
                                $selectedOrderTypes = (array) ($orderType ?? $orderTypeOptions);
                            @endphp
                            @foreach($orderTypeOptions as $ot)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="orderType[]" value="{{ $ot }}" id="ot_{{ $ot }}" {{ in_array($ot, $selectedOrderTypes) ? 'checked' : '' }}>
                                <label class="form-check-label" for="ot_{{ $ot }}">{{ $ot }}</label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Date Range -->
                <div class="col-md-2">
                    <label class="form-label-shadcn">From</label>
                    <input type="date" name="fromDate" class="form-control-shadcn" value="{{ $fromDate }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label-shadcn">To</label>
                    <input type="date" name="toDate" class="form-control-shadcn" value="{{ $toDate }}">
                </div>

                <!-- Filter Button -->
                <div class="col-md-2">
                    <button type="submit" class="btn-shadcn btn-shadcn-primary w-100">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
                        Filter
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Data Table -->
<div class="card-shadcn">
    <div class="card-shadcn-header flex-between">
        <h3 class="card-shadcn-title">Rejected {{ implode(', ', (array)$dataType) }} Data</h3>
        <div class="text-muted" style="font-size: 0.875rem;">
            Showing <strong>{{ $rejected->count() }}</strong> — Total <strong>{{ $total }}</strong>
        </div>
    </div>
    <div class="card-shadcn-body p-0">
        <div style="max-height: 560px; overflow: auto;">
            <table class="table-shadcn" style="width: 100%;">
                <thead>
                    <tr>
                        <th>Request ID</th>
                        <th>Service</th>
                        <th>Data Type</th>
                        <th>Document Date</th>
                        <th>Order Type</th>
                        <th>Ref ID</th>
                        <th>Encounter ID</th>
                        <th>Error Message</th>
                        <th>Request At</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rejected as $item)
                    <tr>
                        <td><code style="font-size: 0.75rem;">{{ $item['requestId'] ?? '-' }}</code></td>
                        <td>{{ $item['service'] ?? '-' }}</td>
                        <td><span class="badge-shadcn badge-shadcn-secondary">{{ $item['dataType'] ?? '-' }}</span></td>
                        <td>{{ $item['documentDate'] ?? '-' }}</td>
                        <td><span class="badge-shadcn badge-shadcn-info">{{ $item['orderType'] ?? '-' }}</span></td>
                        <td style="font-family: monospace; font-size: 0.8125rem;">{{ $item['refId'] ?? '-' }}</td>
                        <td>{{ $item['encounterId'] ?? '-' }}</td>
                        <td>
                            <pre style="white-space: pre-wrap; word-wrap: break-word; margin: 0; font-size: 0.75rem; color: var(--destructive);">{{ $item['errorMessage'] ?? '-' }}</pre>
                        </td>
                        <td style="white-space: nowrap;">{{ $item['requestAt'] ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted" style="padding: 2rem;">No rejected data found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-shadcn-footer">
        <div class="flex-between">
            <a href="{{ request()->fullUrlWithQuery(['page' => max(($currentPage ?? 1) - 1, 1)]) }}" class="btn-shadcn btn-shadcn-outline btn-shadcn-sm {{ ($currentPage ?? 1) <= 1 ? 'disabled' : '' }}" style="{{ ($currentPage ?? 1) <= 1 ? 'opacity: 0.5; pointer-events: none;' : '' }}">
                ← Prev
            </a>
            <span class="text-muted" style="font-size: 0.875rem;">Page {{ $currentPage ?? 1 }} of {{ $lastPage ?? 1 }}</span>
            <a href="{{ request()->fullUrlWithQuery(['page' => min(($currentPage ?? 1) + 1, $lastPage ?? 1)]) }}" class="btn-shadcn btn-shadcn-outline btn-shadcn-sm {{ ($currentPage ?? 1) >= ($lastPage ?? 1) ? 'disabled' : '' }}" style="{{ ($currentPage ?? 1) >= ($lastPage ?? 1) ? 'opacity: 0.5; pointer-events: none;' : '' }}">
                Next →
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Keep dropdown open while interacting
    document.querySelectorAll('.dropdown-menu').forEach(menu => {
        menu.addEventListener('click', e => e.stopPropagation());
    });

    // Helper to update dropdown button label
    function updateLabel(btnId, inputName, defaultText) {
        const checked = Array.from(document.querySelectorAll(`input[name="${inputName}[]"]:checked`)).map(i => i.value);
        const btn = document.getElementById(btnId);
        if (!btn) return;
        const textSpan = btn.childNodes[0];
        if (checked.length === 0) {
            textSpan.textContent = defaultText + ' ';
        } else if (checked.length <= 2) {
            textSpan.textContent = checked.join(', ') + ' ';
        } else {
            textSpan.textContent = `${checked.length} selected `;
        }
    }

    // Select all / clear all
    function selectAll(groupName) {
        document.querySelectorAll(`input[name="${groupName}[]"]`).forEach(cb => cb.checked = true);
        updateLabel(groupName === 'dataType' ? 'btnDataType' : 'btnOrderType', groupName, groupName === 'dataType' ? 'Select Type' : 'Select Order');
    }

    function clearAll(groupName) {
        document.querySelectorAll(`input[name="${groupName}[]"]`).forEach(cb => cb.checked = false);
        updateLabel(groupName === 'dataType' ? 'btnDataType' : 'btnOrderType', groupName, groupName === 'dataType' ? 'Select Type' : 'Select Order');
    }

    // Initialize labels
    document.addEventListener('DOMContentLoaded', function() {
        updateLabel('btnDataType', 'dataType', 'Select Type');
        updateLabel('btnOrderType', 'orderType', 'Select Order');

        document.querySelectorAll('input[name="dataType[]"]').forEach(cb => {
            cb.addEventListener('change', () => updateLabel('btnDataType', 'dataType', 'Select Type'));
        });
        document.querySelectorAll('input[name="orderType[]"]').forEach(cb => {
            cb.addEventListener('change', () => updateLabel('btnOrderType', 'orderType', 'Select Order'));
        });
    });
</script>
@endpush
