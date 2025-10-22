<!DOCTYPE html>
<html>
<head>
    <title>Stock Recap - BIH</title>
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

        /* Navbar/Header */
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

        /* Card dan Table */
        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            background: #fff;
        }

        .table th {
            background-color: #f8f9fa;
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

        td.align-top, th.align-top {
            vertical-align: top !important;
        }

        .table td, 
        .table th {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .table-scroll {
            max-height: 600px;
            overflow-y: auto;
            overflow-x: auto;
            white-space: nowrap;
        }
    </style>
</head>
<body>


<!-- Navbar/Header -->
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
    <div class="d-flex justify-content-between mb-4">
        <div>
            <h3>Stock</h3>
            <p>User: {{ $user }} | Sales Org: {{ $org }}</p>
        </div>
        <a href="{{ url('/home') }}" class="btn btn-outline-primary align-self-start">🏠 Home</a>
    </div>


<div class="container mt-4">
    <!-- <h3>Stock</h3>
    <p>User: {{ $user }} | Sales Org: {{ $org }}</p>
    
    <a href="{{ url('/home') }}" class="btn btn-outline-primary align-self-start">🏠 Home</a> -->

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(isset($error))
        <div class="alert alert-danger">{{ $error }}</div>
    @endif


    {{-- FILTER --}}
    <form method="GET" action="{{ route('stock.index') }}" class="row g-3 mb-3">
        {{-- Type --}}
        <div class="col-md-3">
            <label for="type" class="form-label">Type</label>
            <select name="type" id="type" class="form-select">
                @foreach(['StockConsumption','StockReturn'] as $t)
                    <option value="{{ $t }}" {{ ($type ?? 'StockConsumption') == $t ? 'selected' : '' }}>
                        {{ $t }}
                    </option>
                @endforeach
            </select>
        </div>


        {{-- Status --}}
        <div class="col-md-3">
                <label class="form-label fw-bold">Status</label>
                <div class="dropdown" data-bs-auto-close="outside">
                    <button id="btnStatus" class="btn btn-outline-secondary dropdown-toggle w-100 text-start"
                            type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Select Status
                    </button>

                    <div class="dropdown-menu p-3" style="width:100%">
                        <div class="d-flex justify-content-between mb-2">
                            <small class="text-muted">Choose one or more</small>
                            <div>
                                <button type="button" class="btn btn-sm btn-link p-0 me-2" onclick="selectAll('status')">Select all</button>
                                <button type="button" class="btn btn-sm btn-link p-0 text-danger" onclick="clearAll('status')">Clear</button>
                            </div>
                        </div>
                        
                        @php
                            $statusOptions = ['success','failed','ready to rerun','reversed'];
                            $selectedStatus = (array) ($status ?? $statusOptions);
                        @endphp

                        @foreach($statusOptions as $st)
                <div class="form-check status-option" id="wrap_{{ str_replace(' ', '_', $st) }}">
                    <input class="form-check-input status-check" type="checkbox" 
                           name="status[]" value="{{ $st }}"
                           id="st_{{ str_replace(' ', '_', $st) }}"
                           {{ in_array($st, $selectedStatus) ? 'checked' : '' }}
                           onchange="handleStatusChange()">
                    <label class="form-check-label" for="st_{{ str_replace(' ', '_', $st) }}">{{ ucfirst($st) }}</label>
                </div>
            @endforeach
        </div>
    </div>
</div>
            
        <!-- <div class="col-md-3">
            <label class="form-label fw-bold">Status</label>
            <select name="status[]" class="form-select" multiple>
                @foreach(['success','failed','ready to rerun','reversed'] as $s)
                    <option value="{{ $s }}" {{ in_array($s, $status ?? ['success','failed','ready to rerun','reversed']) ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
        </div> -->

        {{-- Dates --}}
        <div class="col-md-2">
            <label class="form-label fw-bold">From</label>
            <input type="date" name="fromDate" class="form-control" value="{{ $fromDate }}">
        </div>
        <div class="col-md-2">
            <label class="form-label fw-bold">To</label>
            <input type="date" name="toDate" class="form-control" value="{{ $toDate }}">
        </div>

        <div class="col-md-2 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100">Filter</button>
        </div>
    </form>

    <div class="card p-3 mb-4">
        <div class="card-body">
        <h5 class="fw-bold text-dark mb-3">Stock Consumption Recaps</h5>
        </div>
    </div>   

    <div class="card p-3"> 
        <div class="card-body"> 
            <div class="d-flex justify-content-between align-items-center mb-3"> 
                <h5 class="fw-bold text-dark">List Recap</h5> 
                <!-- Export Excel with active filter --> 
                <a href="{{ route('stock.export') }}?{{ http_build_query([
                    'status' => $status,
                    'type' => $type,
                    'fromDate' => $fromDate,
                    'toDate' => $toDate
                ]) }}" class="btn btn-success btn-sm">📊 Export to Excel</a>
                </div> 
            </div>

    {{-- TABLE --}}
    <div class="table-scroll" style="max-height: 600px; overflow-y: auto;">
                <table id="recapTable" class="table table-bordered table-hover align-middle mb-0">
                    <thead class="table-light text-center align-middle">
                        <tr>
                            <th class="sortable" onclick="sortByRecapCode()">Recap Code <span id="recapSortArrow" class="arrow">⬍</span></th>
                            <th>Status Recap Code</th>
                            <th>Error Message Recap</th>
                            <th>Sold To</th>
                            <th>Ship To</th>
                            <th>Bill To</th>
                            <th>Payer</th>
                            <th>List Recap Code</th>
                            <th>Material</th>
                            <th>Quantity</th>
                            <th>Storage Location</th>
                            <th>Batch</th>
                            <th>Doctor</th>
                            <th>Error Message Item</th>
                            <th>Status Item</th>
                            <th>Ref ID</th>
                            <th>Encounter ID</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recaps as $recap)
                            @php
                                $items = collect($recap['items'] ?? []);
                                $rowCount = max($items->count(), 1);
                                $soldTo = $recap['soldTo'] ?? '';
                                $shipTo = $recap['shipTo'] ?? '';
                                $billTo = $recap['billTo'] ?? '';
                                $payer = $recap['payer'] ?? '';
                            @endphp

                            @if($items->isEmpty())
                                <tr>
                                    <td>{{ $recap['recapCode'] ?? '-' }}</td>
                                    <td>{{ $recap['status'] ?? '-' }}</td>
                                    <td>{{ $recap['sapErrorMessage'] ?? '-' }}</td>
                                    <td>{{ $soldTo }}</td>
                                    <td>{{ $shipTo }}</td>
                                    <td>{{ $billTo }}</td>
                                    <td>{{ $payer }}</td>
                                    <td>{{ $recap['recapCode'] ?? '-' }}</td>
                                    <td colspan="9" class="text-center text-muted">No item data available</td>
                                </tr>
                            @else
                                @foreach($items as $index => $item)
                                    @php
                                        $refIds = collect($item['belongsToRefs'] ?? [])
                                            ->map(fn($ref) => e($ref['refId'] ?? ''))
                                            ->filter()
                                            ->unique()
                                            ->values();

                                        $encounterIds = collect($item['belongsToRefs'] ?? [])
                                            ->map(fn($ref) => e($ref['encounterId'] ?? ''))
                                            ->filter()
                                            ->unique()
                                            ->values();
                                    @endphp

                                    <tr>
                                        {{-- tampilkan kolom recap hanya sekali di awal --}}
                                        @if($index === 0)
                                            <td rowspan="{{ $rowCount }}" class="refid align-top">{{ $recap['recapCode'] ?? '-' }}</td>
                                            <td rowspan="{{ $rowCount }}" class="align-top">{{ $recap['status'] ?? '-' }}</td>
                                            <td rowspan="{{ $rowCount }}" class="align-top">{{ $recap['sapErrorMessage'] ?? '' }}</td>
                                            <td rowspan="{{ $rowCount }}" class="align-top">{{ $soldTo }}</td>
                                            <td rowspan="{{ $rowCount }}" class="align-top">{{ $shipTo }}</td>
                                            <td rowspan="{{ $rowCount }}" class="align-top">{{ $billTo }}</td>
                                            <td rowspan="{{ $rowCount }}" class="align-top">{{ $payer }}</td>
                                        @endif

                                        {{-- Kolom item & list recap code tetap per baris --}}
                                        <td>{{ $recap['recapCode'] ?? '-' }}</td>
                                        <td>{{ $item['material'] ?? '-' }}</td>
                                        <td>{{ $item['quantity'] ?? '-' }}</td>
                                        <td>{{ $item['storageLocation'] ?? '-' }}</td>
                                        <td>{{ $item['batch'] ?? '-' }}</td>
                                        <td>{{ $item['doctor'] ?? '-' }}</td>
                                        <td>{{ $item['sapErrorMessage'] ?? '' }}</td>
                                        <td class="{{ $item['status'] == 'success' ? 'text-success' : 'text-danger' }}">
                                            {{ $item['status'] ?? '-' }}
                                        </td>
                                        <td class="refid">
                                            @if($refIds->isNotEmpty())
                                                {!! implode('<br>', $refIds->toArray()) !!}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="refid">
                                            @if($encounterIds->isNotEmpty())
                                                {!! implode('<br>', $encounterIds->toArray()) !!}
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        @empty
                            <tr>
                                <td colspan="17" class="text-center text-muted">No data found for the selected filters.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

    {{-- Pagination --}}
            <div class="d-flex justify-content-between mt-3">
                <a href="{{ request()->fullUrlWithQuery(['page' => max($currentPage - 1, 1)]) }}"
                   class="btn btn-outline-secondary btn-sm {{ $currentPage <= 1 ? 'disabled' : '' }}">← Prev</a>
                <span class="text-muted">Page {{ $currentPage }}</span>
                <a href="{{ request()->fullUrlWithQuery(['page' => $currentPage + 1]) }}"
                   class="btn btn-outline-secondary btn-sm">Next →</a>
            </div>
        </div>
    </div>
</div>

{{-- Footer --}}
<footer class="footer">
    © {{ date('Y') }} <span>Bali International Hospital</span> — Developed by IT Department
</footer>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

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

document.querySelectorAll('.dropdown-menu').forEach(menu => {
        menu.addEventListener('click', e => e.stopPropagation());
    });
    
// === STATUS LOGIC ===
function handleStatusChange() {
    const ready = document.getElementById('wrap_ready_to_rerun');
    const reversed = document.getElementById('wrap_reversed');
    const readyCheck = document.getElementById('st_ready_to_rerun');
    const reversedCheck = document.getElementById('st_reversed');

    if (readyCheck.checked) {
        // hide reversed
        reversed.style.display = 'none';
        reversedCheck.checked = false;
    } else if (reversedCheck.checked) {
        // hide ready to rerun
        ready.style.display = 'none';
        readyCheck.checked = false;
    } else {
        // show all if none selected
        ready.style.display = '';
        reversed.style.display = '';
    }
}

function selectAll(groupName) {
    document.querySelectorAll(`input[name="${groupName}[]"]`).forEach(cb => cb.checked = true);
    handleStatusChange();
    updateLabel('btnStatus', groupName, 'Select Status');
}

function clearAll(groupName) {
    document.querySelectorAll(`input[name="${groupName}[]"]`).forEach(cb => cb.checked = false);
    handleStatusChange();
    updateLabel('btnStatus', groupName, 'Select Status');
}

document.addEventListener('DOMContentLoaded', handleStatusChange);
</script>
</body>
</html>
