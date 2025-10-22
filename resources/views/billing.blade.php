<!DOCTYPE html>
<html>
<head>
    <title>Billing - Bali International Hospital</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/filter-style.css') }}" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #383737ff, #eef3f9);
            font-family: 'Segoe UI', sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .navbar {
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }

        .navbar-brand span {
            color: #000;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .btn-logout {
            border-color: #000;
            color: #000;
        }

        .btn-logout:hover {
            background-color: #000;
            color: #fff;
        }

        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            background: #fff;
        }

        th.sortable {
            cursor: pointer;
            user-select: none;
        }

        th .arrow {
            margin-left: 6px;
            font-size: 0.8rem;
        }

        .table-scroll {
            max-height: 560px;
            overflow-y: auto;
            overflow-x: auto;
        }

        .table-scroll thead th {
            position: sticky;
            top: 0;
            background: #f8f9fa;
            z-index: 3;
        }

        .recap-code-first {
            font-weight: 600;
            color: #0b5ed7;
            width: 220px;
        }

        .list-recap {
            width: 240px;
        }

        .text-currency {
            white-space: nowrap;
        }

        .footer {
            text-align: center;
            color: #999;
            font-size: 14px;
            margin-top: auto;
            padding: 30px 0;
        }

        .footer span {
            color: #004e89;
            font-weight: 500;
        }

        tbody tr:nth-child(even) {
            background-color: #fdfdfd;
        }
        tbody tr:hover {
            background-color: #f3f6f9;
        }
    </style>
</head>
<body>

{{-- Navbar --}}
<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="{{ url('/home') }}">
            <img src="{{ asset('images/bih_logo.png') }}" alt="BIH Logo" style="height: 40px;" class="me-2">
            <span>Bali International Hospital</span>
        </a>
        <div class="d-flex ms-auto">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline-dark btn-sm px-3 btn-logout">Logout</button>
            </form>
        </div>
    </div>
</nav>

<div class="container mt-5 mb-5">

    {{-- User Info --}}
    <div class="d-flex justify-content-between mb-4">
        <div>
            <h4 class="fw-semibold text-dark mb-1">User: {{ $user }}</h4>
            <p class="text-muted mb-0"><strong>Sales Org:</strong> {{ $org }}</p>
        </div>
        <a href="{{ url('/home') }}" class="btn btn-outline-primary align-self-start">🏠 Home</a>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(isset($error))
        <div class="alert alert-danger">{{ $error }}</div>
    @endif

    {{-- Filter --}}
    <form method="GET" class="mb-4">
        <div class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-control bg-light fw-bold">
                    {{ $typeFilter === 'FinalBilling' ? 'Final Billing' : $typeFilter }}
                </label>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="failed" {{ $statusFilter == 'failed' ? 'selected' : '' }}>Failed</option>
                    <option value="success" {{ $statusFilter == 'success' ? 'selected' : '' }}>Success</option>
                    <option value="ready to rerun" {{ $statusFilter == 'ready to rerun' ? 'selected' : '' }}>Ready to rerun</option>
                    <option value="reversed" {{ $statusFilter == 'reversed' ? 'selected' : '' }}>Reversed</option>
                </select>
            </div>
            <div class="col-md-2">
                <input type="date" name="fromDate" class="form-control" value="{{ $fromDate }}">
            </div>
            <div class="col-md-2">
                <input type="date" name="toDate" class="form-control" value="{{ $toDate }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
        </div>
    </form>

    {{-- Summary --}}
    <div class="card p-3 mb-4">
        <div class="card-body">
            <div class="row align-items-start">
                <div class="col-md-6">
                    <h5 class="fw-bold text-dark mb-3">Final Billing Recaps</h5>
                    <p><strong>Date Range:</strong> {{ $fromDate }} to {{ $toDate }}</p>
                    <p><strong>Status:</strong> {{ ucfirst($statusFilter) }}</p>
                    <p><strong>Total Recaps:</strong> {{ $recaps->count() }}</p>
                </div>
                <div class="col-md-6 text-end">
                    <h6>Total Final Amount:
                        <span class="text-black">Rp {{ number_format($totalFinalAmount ?? 0, 0, ',', '.') }}</span>
                    </h6>
                    <h6>Total Amount Free:
                        <span class="text-black">Rp {{ number_format($totalAmountFree ?? 0, 0, ',', '.') }}</span>
                    </h6>
                    <h5 class="fw-bold">Total Amount:
                        <span class="text-black">Rp {{ number_format($totalAmount ?? 0, 0, ',', '.') }}</span>
                    </h5>
                    <h6>Total Deposit Amount:
                        <span class="text-black">Rp {{ number_format($totalDepositAmount ?? 0, 0, ',', '.') }}</span>
                    </h6>
                    <h6>Total Recap Code:
                        <span class="text-info">{{ $totalData ?? 0 }}</span>
                    </h6>
                    <h6>Recap on this page:
                        <span class="text-secondary">{{ $recapCount ?? 0 }}</span>
                    </h6>
                </div>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="card p-3">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold text-dark">List Recap</h5>

                 <!-- Export Excel with active filter -->
                <a href="{{ route('billing.export', [
                    'status' => $statusFilter,
                    'type' => $typeFilter,
                    'fromDate' => $fromDate,
                    'toDate' => $toDate
                ]) }}" class="btn btn-success btn-sm">📊 Export to Excel</a>
            </div>

            <div class="table-scroll">
                <table id="recapTable" class="table table-bordered table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="sortable" onclick="sortByRecapCode()">Recap Code <span id="recapSortArrow" class="arrow">⬍</span></th>
                            <th>List Recap Code</th>
                            <th>Ref ID</th>
                            <th>Status</th>
                            <th class="text-end">Final Amount</th>
                            <th>SAP Error</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recaps as $recap)
                            @php
                                $refIds = collect($recap['items'] ?? [])
                                    ->flatMap(fn($item) => collect($item['belongsToRefs'] ?? [])->pluck('refId'))
                                    ->filter()
                                    ->unique()
                                    ->values();
                                if ($refIds->isEmpty()) $refIds = collect([null]);
                                $itemsWithError = collect($recap['items'] ?? [])
                                    ->filter(fn($item) => !is_null($item['sapErrorMessage']));
                            @endphp

                            @foreach($refIds as $idx => $ref)
                                <tr>
                                    @if($idx === 0)
                                        <td class="recap-code-first" rowspan="{{ $refIds->count() }}">{{ $recap['recapCode'] }}</td>
                                    @endif
                                    <td class="list-recap">{{ $recap['recapCode'] }}</td>
                                    <td class="refid">{{ $ref ?? '-' }}</td>
                                    <td>
                                        <span class="badge {{ strtolower($recap['status']) === 'success' ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $recap['status'] }}
                                        </span>
                                    </td>
                                    <td class="text-end text-currency">Rp{{ number_format($recap['totalFinalAmount'] ?? 0, 0, ',', '.') }}</td>
                                    <td>
                                        @if(!is_null($recap['sapErrorMessage']))
                                            <div><strong>Recap Error:</strong><br>{!! nl2br(e($recap['sapErrorMessage'])) !!}</div>
                                        @elseif($itemsWithError->count() > 0)
                                            <ul class="mb-0">
                                                @foreach ($itemsWithError as $item)
                                                    <li>
                                                        <strong>Material:</strong> {{ $item['material'] }} — 
                                                        <strong>Qty:</strong> {{ $item['quantity'] }} — 
                                                        <strong>Amount:</strong> Rp{{ number_format($item['finalAmount'] ?? 0, 0, ',', '.') }}<br>
                                                        <strong>Error:</strong> {{ $item['sapErrorMessage'] }}
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @else
                                            Tidak ada error item.
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @empty
                            <tr><td colspan="6" class="text-center text-muted">Tidak ada data.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="d-flex justify-content-between mt-3">
                <a href="{{ request()->fullUrlWithQuery(['page' => max(($currentPage ?? 1) - 1, 1)]) }}"
                   class="btn btn-outline-secondary btn-sm {{ ($currentPage ?? 1) <= 1 ? 'disabled' : '' }}">← Prev</a>
                <span class="text-muted">Page {{ $currentPage ?? 1 }} of {{ $lastPage ?? 1 }}</span>
                <a href="{{ request()->fullUrlWithQuery(['page' => min(($currentPage ?? 1) + 1, $lastPage ?? 1)]) }}"
                   class="btn btn-outline-secondary btn-sm {{ ($currentPage ?? 1) >= ($lastPage ?? 1) ? 'disabled' : '' }}">Next →</a>
            </div>
        </div>
    </div>
</div>

<footer class="footer">
    © {{ date('Y') }} <span>Bali International Hospital</span> — Developed by IT Department
</footer>

<script>
    let sortAscending = true;
    function sortByRecapCode() {
        const table = document.getElementById('recapTable');
        const tbody = table.querySelector('tbody');
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
</script>

</body>
</html>
