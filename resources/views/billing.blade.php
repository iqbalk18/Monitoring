@extends('layouts.app')

@section('title', 'Billing - Bali International Hospital')

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
            <h2 class="section-title">Billing Dashboard</h2>
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
    @if(isset($error))
        <div class="alert-shadcn alert-shadcn-destructive mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10" />
                <line x1="15" x2="9" y1="9" y2="15" />
                <line x1="9" x2="15" y1="9" y2="15" />
            </svg>
            <div class="alert-description">{{ $error }}</div>
        </div>
    @endif

    <!-- Filter Form -->
    <div class="card-shadcn mb-4">
        <div class="card-shadcn-body">
            <form method="GET">
                <div class="row g-3 align-items-end">
                    <!-- Type (readonly) -->
                    <div class="col-md-2">
                        <label class="form-label-shadcn">Type</label>
                        <div class="form-control-shadcn" style="background: var(--muted); cursor: not-allowed;">
                            {{ $typeFilter === 'FinalBilling' ? 'Final Billing' : $typeFilter }}
                        </div>
                    </div>

                    <!-- Status Dropdown -->
                    <div class="col-md-2">
                        <label class="form-label-shadcn">Status</label>
                        <div class="dropdown" data-bs-auto-close="outside">
                            <button id="btnStatus" class="btn-shadcn btn-shadcn-outline w-100 justify-content-between"
                                type="button" data-bs-toggle="dropdown" aria-expanded="false" style="height: 2.5rem;">
                                Select Status
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="m6 9 6 6 6-6" />
                                </svg>
                            </button>
                            <div class="dropdown-menu dropdown-menu-shadcn p-3" style="min-width: 200px;">
                                <div class="d-flex justify-content-between mb-2">
                                    <small class="text-muted">Choose one or more</small>
                                    <div>
                                        <button type="button" class="btn btn-sm btn-link p-0 me-2"
                                            onclick="selectAll('status')">All</button>
                                        <button type="button" class="btn btn-sm btn-link p-0 text-danger"
                                            onclick="clearAll('status')">Clear</button>
                                    </div>
                                </div>
                                @php
                                    $statusOptions = ['success', 'failed', 'ready to rerun', 'reversed'];
                                    $selectedStatus = (array) ($status ?? $statusOptions);
                                @endphp
                                @foreach($statusOptions as $st)
                                    <div class="form-check status-option" id="wrap_{{ str_replace(' ', '_', $st) }}">
                                        <input class="form-check-input status-check" type="checkbox" name="status[]"
                                            value="{{ $st }}" id="st_{{ str_replace(' ', '_', $st) }}" {{ in_array($st, $selectedStatus) ? 'checked' : '' }} onchange="handleStatusChange()">
                                        <label class="form-check-label"
                                            for="st_{{ str_replace(' ', '_', $st) }}">{{ ucfirst($st) }}</label>
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

                    <!-- Ref ID -->
                    <div class="col-md-2">
                        <label class="form-label-shadcn">Ref ID</label>
                        <input type="text" name="refId" class="form-control-shadcn" value="{{ $refId ?? '' }}"
                            placeholder="Search Ref ID...">
                    </div>

                    <!-- Recap Code -->
                    <div class="col-md-2">
                        <label class="form-label-shadcn">Recap Code</label>
                        <input type="text" name="recapCode" class="form-control-shadcn" value="{{ $recapCode ?? '' }}"
                            placeholder="Search Recap Code...">
                    </div>

                    <!-- Filter Button -->
                    <div class="col-md-2">
                        <button type="submit" class="btn-shadcn btn-shadcn-primary w-100">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3" />
                            </svg>
                            Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Card -->
    <div class="card-shadcn mb-4">
        <div class="card-shadcn-body">
            <div class="row align-items-start">
                <div class="col-md-6">
                    <h5 class="fw-semibold mb-3">Final Billing Recaps</h5>
                    <p class="mb-1" style="font-size: 0.875rem;"><strong>Date Range:</strong> {{ $fromDate }} to
                        {{ $toDate }}
                    </p>
                    <p class="mb-1" style="font-size: 0.875rem;"><strong>Status:</strong>
                        {{ implode(', ', array_map('ucfirst', $status)) }}</p>
                    <p class="mb-0" style="font-size: 0.875rem;"><strong>Total Recaps:</strong> {{ $recaps->count() }}</p>
                </div>
                <div class="col-md-6 text-end">
                    <div class="mb-1">
                        <span class="text-muted" style="font-size: 0.875rem;">Total Final Amount:</span>
                        <strong>Rp {{ number_format($totalFinalAmount ?? 0, 0, ',', '.') }}</strong>
                    </div>
                    <div class="mb-1">
                        <span class="text-muted" style="font-size: 0.875rem;">Total Amount Free:</span>
                        <strong>Rp {{ number_format($totalAmountFree ?? 0, 0, ',', '.') }}</strong>
                    </div>
                    <div class="mb-1">
                        <span class="text-muted" style="font-size: 0.875rem;">Total Amount:</span>
                        <strong style="font-size: 1.125rem;">Rp {{ number_format($totalAmount ?? 0, 0, ',', '.') }}</strong>
                    </div>
                    <div class="mb-1">
                        <span class="text-muted" style="font-size: 0.875rem;">Total Deposit:</span>
                        <strong>Rp {{ number_format($totalDepositAmount ?? 0, 0, ',', '.') }}</strong>
                    </div>
                    <div class="mt-2">
                        <span class="badge-shadcn badge-shadcn-info">{{ $totalData ?? 0 }} total</span>
                        <span class="badge-shadcn badge-shadcn-secondary">{{ $recapCount ?? 0 }} on page</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="card-shadcn">
        <div class="card-shadcn-header flex-between">
            <h3 class="card-shadcn-title">List Recap</h3>
            <a href="{{ route('billing.export') }}?{{ http_build_query([
        'status' => $status,
        'type' => $typeFilter,
        'fromDate' => $fromDate,
        'toDate' => $toDate,
        'refId' => $refId ?? null,
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
        <div class="card-shadcn-body p-0">
            <div style="max-height: 560px; overflow: auto;">
                <table class="table-shadcn" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Ref ID</th>
                            <th>Document Date</th>
                            <th>Order Type</th>
                            <th style="cursor: pointer; min-width: 200px;" onclick="sortByRecapCode()">Recap Code <span
                                    id="recapSortArrow">⬍</span></th>
                            <th>FB Status</th>
                            <th>Recap Status</th>
                            <th>Payer</th>
                            <th>Assignment</th>
                            <th style="text-align: right;">Deposit Amount</th>
                            <th style="text-align: right;">Final Amount</th>
                            <th style="text-align: right;">Amount Free</th>
                            <th style="text-align: right;">Total Amount</th>
                        </tr>
                    </thead>
                    <tbody id="recapTableBody">
                        @forelse($recaps as $recap)
                            @php
                                $refs = collect($recap['refs'] ?? []);
                                if ($refs->isEmpty()) {
                                    $refs = collect([
                                        [
                                            'refId' => '-',
                                            'documentDate' => $recap['documentDate'] ?? '-',
                                            'depositAmount' => 0,
                                            'totalFinalAmount' => $recap['totalFinalAmount'] ?? 0,
                                            'totalAmountFree' => $recap['totalAmountFree'] ?? 0,
                                            'totalAmount' => $recap['totalAmount'] ?? 0,
                                        ]
                                    ]);
                                }
                            @endphp

                            @foreach($refs as $ref)
                                <tr>
                                    <td style="font-family: monospace; font-size: 0.8125rem;">{{ $ref['refId'] ?? '-' }}</td>
                                    <td>{{ $ref['documentDate'] ?? '-' }}</td>
                                    <td>{{ $recap['orderType'] ?? '-' }}</td>
                                    <td class="list-recap" style="font-weight: 600; color: var(--brand);">{{ $recap['recapCode'] }}
                                    </td>
                                    <td>{{ $recap['fbStatus'] ?? '-' }}</td>
                                    <td>
                                        <span
                                            class="badge-shadcn {{ strtolower($recap['status']) === 'success' ? 'badge-shadcn-success' : 'badge-shadcn-secondary' }}">
                                            {{ $recap['status'] }}
                                        </span>
                                    </td>
                                    <td>{{ $recap['payer'] ?? '-' }}</td>
                                    <td>{{ $recap['assignment'] ?? '-' }}</td>
                                    <td style="text-align: right;">Rp{{ number_format($ref['depositAmount'] ?? 0, 0, ',', '.') }}
                                    </td>
                                    <td style="text-align: right;">Rp{{ number_format($ref['totalFinalAmount'] ?? 0, 0, ',', '.') }}
                                    </td>
                                    <td style="text-align: right;">Rp{{ number_format($ref['totalAmountFree'] ?? 0, 0, ',', '.') }}
                                    </td>
                                    <td style="text-align: right;">Rp{{ number_format($ref['totalAmount'] ?? 0, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        @empty
                            <tr>
                                <td colspan="12" class="text-center text-muted" style="padding: 2rem;">No data found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
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
    </div>
@endsection

@push('scripts')
    <script>
        let sortAscending = true;

        function sortByRecapCode() {
            const tbody = document.getElementById('recapTableBody');
            const rows = Array.from(tbody.querySelectorAll('tr')).filter(r => r.querySelector('.list-recap'));

            rows.sort((a, b) => {
                const codeA = (a.querySelector('.list-recap')?.textContent || '').trim();
                const codeB = (b.querySelector('.list-recap')?.textContent || '').trim();
                return sortAscending ? codeA.localeCompare(codeB) : codeB.localeCompare(codeA);
            });

            tbody.innerHTML = '';
            rows.forEach(r => tbody.appendChild(r));

            const arrow = document.getElementById('recapSortArrow');
            arrow.textContent = sortAscending ? '▲' : '▼';
            sortAscending = !sortAscending;
        }

        // Dropdown menu interaction
        document.querySelectorAll('.dropdown-menu').forEach(menu => {
            menu.addEventListener('click', e => e.stopPropagation());
        });

        // Status Logic
        function handleStatusChange() {
            const ready = document.getElementById('wrap_ready_to_rerun');
            const reversed = document.getElementById('wrap_reversed');
            const readyCheck = document.getElementById('st_ready_to_rerun');
            const reversedCheck = document.getElementById('st_reversed');

            if (readyCheck && readyCheck.checked) {
                if (reversed) reversed.style.display = 'none';
                if (reversedCheck) reversedCheck.checked = false;
            } else if (reversedCheck && reversedCheck.checked) {
                if (ready) ready.style.display = 'none';
                if (readyCheck) readyCheck.checked = false;
            } else {
                if (ready) ready.style.display = '';
                if (reversed) reversed.style.display = '';
            }
            updateStatusLabel();
        }

        function selectAll(groupName) {
            document.querySelectorAll(`input[name="${groupName}[]"]`).forEach(cb => cb.checked = true);
            handleStatusChange();
        }

        function clearAll(groupName) {
            document.querySelectorAll(`input[name="${groupName}[]"]`).forEach(cb => cb.checked = false);
            handleStatusChange();
        }

        function updateStatusLabel() {
            const checked = Array.from(document.querySelectorAll('input[name="status[]"]:checked')).map(i => i.value);
            const btn = document.getElementById('btnStatus');
            if (!btn) return;
            const textSpan = btn.childNodes[0];
            if (checked.length === 0) {
                textSpan.textContent = 'Select Status ';
            } else if (checked.length <= 2) {
                textSpan.textContent = checked.map(s => s.charAt(0).toUpperCase() + s.slice(1)).join(', ') + ' ';
            } else {
                textSpan.textContent = `${checked.length} selected `;
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            handleStatusChange();
            updateStatusLabel();
        });
    </script>
@endpush