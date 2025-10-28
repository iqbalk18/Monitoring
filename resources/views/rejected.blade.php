<!DOCTYPE html>
<html>
<head>
    <title>Rejected - Bali International Hospital</title>
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
            background-color: #fff; box-shadow: 0 2px 10px rgba(0,0,0,0.08); 
        }
        .card { 
            border: none; 
            border-radius: 1rem; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.05); 
            background: #fff; 
        }
        
        .table-scroll { 
            max-height: 560px; 
            overflow-y: auto; 
            overflow-x: auto; 
        }
        .table-scroll thead th { 
            position: sticky; 
            top: 0; 
            background:
            #f8f9fa; z-index: 3; 
        }
        tbody tr:nth-child(even) { 
            background-color: #fdfdfd; 
        } 
        tbody tr:hover { 
            background-color: 
            #f3f6f9; 
        }
        pre { 
            white-space: pre-wrap; 
            word-wrap: break-word; 
            margin:0; 
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="{{ url('/home') }}">
            <img src="{{ asset('images/bih_logo.png') }}" alt="BIH Logo" style="height: 40px;" class="me-2">
            <span>Bali International Hospital</span>
        </a>
        <div class="d-flex ms-auto">
            <form method="GET" action="{{ route('dashboard') }}"class="me-2">
                @csrf
                <button type="submit" class="btn btn-outline-primary btn-sm px-3 btn-logout">Home</button>
            </form>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline-danger btn-sm px-3">Logout</button>
            </form>
        </div>
    </div>
</nav>

<div class="container mt-5 mb-5">
    <div class="d-flex justify-content-between mb-4">
        <div>
            <h4 class="fw-semibold text-dark mb-1">User: {{ $user }}</h4>
            <p class="text-muted mb-0"><strong>Sales Org:</strong> {{ $org }}</p>
        </div>
        <!-- <a href="{{ url('/home') }}" class="btn btn-outline-primary align-self-start">🏠 Home</a> -->
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(isset($error))
        <div class="alert alert-danger">{{ $error }}</div>
    @endif

    {{-- FILTER FORM --}}
    <form method="GET" action="{{ route('rejected.index') }}">
        <div class="row mb-3 align-items-end">
            {{-- DataType dropdown (multi checkbox) --}}
            <div class="col-md-3">
                <label class="form-label fw-bold">Data Type</label>
                <div class="dropdown" data-bs-auto-close="outside">
                    <button id="btnDataType" class="btn btn-outline-secondary dropdown-toggle w-100 text-start"
                            type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Select Data Type
                    </button>

                    <div class="dropdown-menu p-3" style="width:100%">
                        <div class="d-flex justify-content-between mb-2">
                            <small class="text-muted">Choose one or more</small>
                            <div>
                                <button type="button" class="btn btn-sm btn-link p-0 me-2" onclick="selectAll('dataType')">Select all</button>
                                <button type="button" class="btn btn-sm btn-link p-0 text-danger" onclick="clearAll('dataType')">Clear</button>
                            </div>
                        </div>

                        @php
                            $dataTypeOptions = ['Billing','StockConsumption','StockReturn'];
                            $selectedDataTypes = (array) ($dataType ?? $dataTypeOptions);
                        @endphp

                        @foreach($dataTypeOptions as $dt)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="dataType[]" value="{{ $dt }}"
                                       id="dt_{{ $dt }}" {{ in_array($dt, $selectedDataTypes) ? 'checked' : '' }}>
                                <label class="form-check-label" for="dt_{{ $dt }}">{{ $dt }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- OrderType dropdown (multi checkbox) --}}
            <div class="col-md-3">
                <label class="form-label fw-bold">Order Type</label>
                <div class="dropdown" data-bs-auto-close="outside">
                    <button id="btnOrderType" class="btn btn-outline-secondary dropdown-toggle w-100 text-start"
                            type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Select Order Type
                    </button>

                    <div class="dropdown-menu p-3" style="width:100%">
                        <div class="d-flex justify-content-between mb-2">
                            <small class="text-muted">Choose one or more</small>
                            <div>
                                <button type="button" class="btn btn-sm btn-link p-0 me-2" onclick="selectAll('orderType')">Select all</button>
                                <button type="button" class="btn btn-sm btn-link p-0 text-danger" onclick="clearAll('orderType')">Clear</button>
                            </div>
                        </div>

                        @php
                            $orderTypeOptions = ['ZJSC','ZJME','ZJBJ','ZJBC','ZISC','ZIRE','ZIM2','ZIM1','ZIBJ','ZIBC'];
                            $selectedOrderTypes = (array) ($orderType ?? $orderTypeOptions);
                        @endphp

                        @foreach($orderTypeOptions as $ot)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="orderType[]" value="{{ $ot }}"
                                       id="ot_{{ $ot }}" {{ in_array($ot, $selectedOrderTypes) ? 'checked' : '' }}>
                                <label class="form-check-label" for="ot_{{ $ot }}">{{ $ot }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Dates --}}
            <div class="col-md-2">
                <label class="form-label fw-semibold">From</label>
                <input type="date" name="fromDate" class="form-control" value="{{ $fromDate }}">
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold">To</label>
                <input type="date" name="toDate" class="form-control" value="{{ $toDate }}">
            </div>

            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
        </div>
    </form>

    {{-- TABLE --}}
    <div class="card p-3">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold text-dark">Rejected {{ implode(', ', (array)$dataType) }} Data</h5>
                <div class="text-muted small">Showing <strong>{{ $rejected->count() }}</strong> — Total <strong>{{ $total }}</strong></div>
            </div>

            <div class="table-scroll">
                <table class="table table-bordered table-hover align-middle mb-0">
                    <thead class="table-light">
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
                                <td><code>{{ $item['requestId'] ?? '-' }}</code></td>
                                <td>{{ $item['service'] ?? '-' }}</td>
                                <td>{{ $item['dataType'] ?? '-' }}</td>
                                <td>{{ $item['documentDate'] ?? '-' }}</td>
                                <td>{{ $item['orderType'] ?? '-' }}</td>
                                <td class="refid">{{ $item['refId'] ?? '-' }}</td>
                                <td>{{ $item['encounterId'] ?? '-' }}</td>
                                <td><pre class="text-danger small m-0">{{ $item['errorMessage'] ?? '-' }}</pre></td>
                                <td>{{ $item['requestAt'] ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted">No rejected data found.</td>
                            </tr>
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

<footer class="footer mt-auto">
    <div class="container text-center py-3">
        © {{ date('Y') }} <span>Bali International Hospital</span> — Developed by IT Department
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Keep dropdown open while interacting (we used data-bs-auto-close="outside"), but still stop propagation as safety
    document.querySelectorAll('.dropdown-menu').forEach(menu => {
        menu.addEventListener('click', e => e.stopPropagation());
    });

    // Helper to update dropdown button label based on selected checkboxes
    function updateLabel(btnId, inputName, defaultText) {
        const checked = Array.from(document.querySelectorAll(`input[name="${inputName}[]"]:checked`)).map(i => i.value);
        const btn = document.getElementById(btnId);
        if (!btn) return;
        if (checked.length === 0) {
            btn.textContent = defaultText;
        } else if (checked.length <= 2) {
            btn.textContent = checked.join(', ');
        } else {
            btn.textContent = `${checked.length} selected`;
        }
    }

    // Select all / clear all for a given checkbox group
    function selectAll(groupName) {
        document.querySelectorAll(`input[name="${groupName}[]"]`).forEach(cb => cb.checked = true);
        updateLabel(groupName === 'dataType' ? 'btnDataType' : 'btnOrderType', groupName, groupName === 'dataType' ? 'Select Data Type' : 'Select Order Type');
    }
    function clearAll(groupName) {
        document.querySelectorAll(`input[name="${groupName}[]"]`).forEach(cb => cb.checked = false);
        updateLabel(groupName === 'dataType' ? 'btnDataType' : 'btnOrderType', groupName, groupName === 'dataType' ? 'Select Data Type' : 'Select Order Type');
    }

    // initialize labels and add change listeners
    document.addEventListener('DOMContentLoaded', function() {
        updateLabel('btnDataType', 'dataType', 'Select Data Type');
        updateLabel('btnOrderType', 'orderType', 'Select Order Type');

        document.querySelectorAll('input[name="dataType[]"]').forEach(cb => {
            cb.addEventListener('change', () => updateLabel('btnDataType', 'dataType', 'Select Data Type'));
        });
        document.querySelectorAll('input[name="orderType[]"]').forEach(cb => {
            cb.addEventListener('change', () => updateLabel('btnOrderType', 'orderType', 'Select Order Type'));
        });
    });
</script>

</body>
</html>
