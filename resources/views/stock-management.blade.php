@extends('layouts.app')

@section('title', 'Stock Management - Bali International Hospital')

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <style>
        /* DataTables overrides for shadcn style */
        .dataTables_wrapper .dataTables_length select {
            height: 32px;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 0 2rem 0 0.75rem;
            font-size: 0.875rem;
            background-color: var(--card);
            color: var(--foreground);
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%2371717a' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.5rem center;
            background-repeat: no-repeat;
            background-size: 1.25em 1.25em;
        }

        .dataTables_wrapper .dataTables_filter input {
            height: 32px;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 0 0.75rem;
            font-size: 0.875rem;
            background-color: var(--card);
            color: var(--foreground);
        }

        .dataTables_wrapper .dataTables_length select:focus,
        .dataTables_wrapper .dataTables_filter input:focus {
            outline: none;
            border-color: var(--ring);
            box-shadow: 0 0 0 3px rgb(24 24 27 / 0.1);
        }

        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_length label,
        .dataTables_wrapper .dataTables_filter label {
            font-size: 0.875rem;
            color: var(--foreground);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .dataTables_wrapper .dataTables_filter {
            display: none !important;
        }

        /* Hide empty top controls wrapper - target rows containing length/filter */
        .dataTables_wrapper>.row:first-child,
        .dataTables_wrapper>.row:has(.dataTables_length):not(:has(.dataTables_info)),
        .dataTables_wrapper>div:first-child:not(.table-container-shadcn):not(table) {
            display: none !important;
        }

        .dataTables_wrapper {
            margin-top: 0 !important;
            padding-top: 0 !important;
        }

        /* Ensure no spacing before table */
        .dataTables_wrapper table,
        .dataTables_wrapper>.row:has(table) {
            margin-top: 0 !important;
        }

        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter {
            margin-bottom: 0 !important;
        }

        /* Compact shadcn pagination - force override */
        .dataTables_wrapper .dataTables_paginate {
            float: right !important;
            text-align: right !important;
        }

        .dataTables_wrapper .dataTables_paginate .pagination {
            display: inline-flex !important;
            align-items: center !important;
            gap: 4px !important;
            margin: 0 !important;
            padding: 0 !important;
            list-style: none !important;
        }

        .dataTables_wrapper .dataTables_paginate .pagination .page-item {
            margin: 0 !important;
            padding: 0 !important;
        }

        .dataTables_wrapper .dataTables_paginate .pagination .page-item .page-link,
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            min-width: 32px !important;
            height: 32px !important;
            padding: 0 8px !important;
            font-size: 0.875rem !important;
            font-weight: 500 !important;
            line-height: 1 !important;
            border-radius: 6px !important;
            border: none !important;
            background: transparent !important;
            color: var(--foreground) !important;
            margin: 0 !important;
            transition: all 0.15s ease !important;
            cursor: pointer !important;
            box-shadow: none !important;
        }

        .dataTables_wrapper .dataTables_paginate .pagination .page-item .page-link:hover,
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover:not(.disabled):not(.current) {
            background: var(--muted) !important;
            color: var(--foreground) !important;
        }

        .dataTables_wrapper .dataTables_paginate .pagination .page-item.active .page-link,
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: var(--primary) !important;
            color: var(--primary-foreground) !important;
        }

        .dataTables_wrapper .dataTables_paginate .pagination .page-item.disabled .page-link,
        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
            opacity: 0.3 !important;
            cursor: not-allowed !important;
            pointer-events: none !important;
            background: transparent !important;
        }

        /* Hide ellipsis buttons */
        .dataTables_wrapper .dataTables_paginate .ellipsis,
        .dataTables_wrapper .dataTables_paginate .page-item .page-link.ellipsis {
            display: none !important;
        }

        /* Hide empty page links */
        .dataTables_wrapper .dataTables_paginate .paginate_button.first:empty,
        .dataTables_wrapper .dataTables_paginate .paginate_button.last:empty,
        .dataTables_wrapper .dataTables_paginate .page-item .page-link:empty {
            display: none !important;
        }

        /* Previous/Next button styling when disabled - make them less visible */
        .dataTables_wrapper .dataTables_paginate .page-item:first-child .page-link[aria-disabled="true"],
        .dataTables_wrapper .dataTables_paginate .page-item:last-child .page-link[aria-disabled="true"],
        .dataTables_wrapper .dataTables_paginate .paginate_button.previous.disabled,
        .dataTables_wrapper .dataTables_paginate .paginate_button.next.disabled {
            border: none !important;
            background: none !important;
            opacity: 0.25 !important;
        }

        /* Hide length menu from top row */
        .dataTables_wrapper>.row:first-child .dataTables_length {
            display: none !important;
        }

        /* Bottom wrapper - info + length left, pagination right */
        .dataTables_wrapper .dataTables_info {
            padding-top: 0 !important;
            display: inline-flex !important;
            align-items: center !important;
        }

        .dataTables_wrapper>.row:last-child {
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
            padding: 0.75rem 1rem !important;
            border-top: 1px solid var(--border) !important;
            margin: 0 !important;
            flex-wrap: nowrap !important;
        }

        .dataTables_wrapper>.row:last-child>div {
            width: auto !important;
            max-width: none !important;
            flex: none !important;
            padding: 0 !important;
        }

        /* Left side container for info + length */
        .dataTables_wrapper>.row:last-child>div:first-child {
            display: flex !important;
            align-items: center !important;
            gap: 1rem !important;
        }

        /* Cloned length menu beside info - inline flex */
        .dataTables_wrapper .bottom-length-menu {
            display: inline-flex !important;
            align-items: center !important;
            float: none !important;
        }

        .dataTables_wrapper .bottom-length-menu label {
            margin-bottom: 0 !important;
        }

        /* Progress Bar Styles */
        .progress-shadcn {
            height: 24px;
            background-color: hsl(var(--muted));
            border-radius: var(--radius);
            overflow: hidden;
            position: relative;
        }

        .progress-bar-shadcn {
            height: 100%;
            background: linear-gradient(90deg, hsl(var(--primary)) 0%, hsl(var(--primary)) 100%);
            border-radius: var(--radius);
            transition: width 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 600;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .progress-bar-shadcn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            animation: shimmer 2s infinite;
        }

        @keyframes shimmer {
            0% {
                left: -100%;
            }

            100% {
                left: 100%;
            }
        }

        .text-xs {
            font-size: 0.75rem;
            line-height: 1rem;
        }

        /* Override container width for this page */
        .container-shadcn {
            max-width: 95% !important;
        }
    </style>
@endpush

@section('content')
    <!-- Page Header -->
    <div class="flex-between mb-4" style="flex-wrap: wrap; gap: 1rem;">
        <div>
            <h2 class="section-title">Stock Management</h2>
            <p class="section-desc">Manage stock imports, calculations, and downloads.</p>
        </div>
    </div>

    <!-- Alerts -->
    <div id="alertContainer">
        @if(session('success'))
            <div class="alert-shadcn alert-shadcn-success" role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                    <polyline points="22 4 12 14.01 9 11.01" />
                </svg>
                <div>
                    <div class="alert-title">Success</div>
                    <div class="alert-description">{{ session('success') }}</div>
                </div>
            </div>
        @endif
        @if(session('error'))
            <div class="alert-shadcn alert-shadcn-destructive" role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10" />
                    <line x1="15" x2="9" y1="9" y2="15" />
                    <line x1="9" x2="15" y1="9" y2="15" />
                </svg>
                <div>
                    <div class="alert-title">Error</div>
                    <div class="alert-description">{{ session('error') }}</div>
                </div>
            </div>
        @endif
        @if($errors->any())
            <div class="alert-shadcn alert-shadcn-destructive" role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10" />
                    <line x1="15" x2="9" y1="9" y2="15" />
                    <line x1="9" x2="15" y1="9" y2="15" />
                </svg>
                <div>
                    <div class="alert-title">Validation Error</div>
                    <ul class="alert-description mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
    </div>

    <!-- Action Buttons -->
    <div class="card-shadcn mb-4">
        <div class="card-shadcn-body">
            <div class="d-flex flex-wrap align-items-center" style="gap: 0.75rem;">
                <!-- Left side buttons -->
                <button type="button" class="btn-shadcn btn-shadcn-primary" data-bs-toggle="modal"
                    data-bs-target="#modalImportExcel">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                        <polyline points="17 8 12 3 7 8" />
                        <line x1="12" x2="12" y1="3" y2="15" />
                    </svg>
                    Import Excel
                </button>
                <button type="button" class="btn-shadcn btn-shadcn-secondary" data-bs-toggle="modal"
                    data-bs-target="#modalDownloadJson">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                        <polyline points="7 10 12 15 17 10" />
                        <line x1="12" x2="12" y1="15" y2="3" />
                    </svg>
                    Download JSON
                </button>
                <button type="button" class="btn-shadcn btn-shadcn-outline" data-bs-toggle="modal"
                    data-bs-target="#modalSaveManual">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                        <polyline points="17 21 17 13 7 13 7 21" />
                        <polyline points="7 3 7 8 15 8" />
                    </svg>
                    Save Manual
                </button>

                <!-- Right side: Period Date + Calculate -->
                <div class="d-flex align-items-center" style="gap: 0.5rem; margin-left: auto;">
                    <label for="period_date_inline" class="form-label-shadcn mb-0" style="white-space: nowrap;">Period
                        Date:</label>
                    <input type="date" class="form-control-shadcn" id="period_date_inline" name="period_date_inline"
                        style="width: auto; min-width: 160px;">
                    <button type="button" class="btn-shadcn btn-shadcn-info" id="btnCompareInline">
                        <span class="spinner-border spinner-border-sm d-none" id="spinnerCompareInline" role="status"
                            aria-hidden="true"></span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="feather feather-git-pull-request">
                            <circle cx="18" cy="18" r="3"></circle>
                            <circle cx="6" cy="6" r="3"></circle>
                            <path d="M13 6h3a2 2 0 0 1 2 2v7"></path>
                            <line x1="6" x2="6" y1="9" y2="21"></line>
                        </svg>
                        Compare
                    </button>
                    <button type="button" class="btn-shadcn btn-shadcn-success" id="btnKalkulasiInline">
                        <span class="spinner-border spinner-border-sm d-none" id="spinnerKalkulasiInline" role="status"
                            aria-hidden="true"></span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect width="16" height="20" x="4" y="2" rx="2" />
                            <line x1="8" x2="16" y1="6" y2="6" />
                            <line x1="16" x2="16" y1="14" y2="18" />
                            <path d="M16 10h.01" />
                            <path d="M12 10h.01" />
                            <path d="M8 10h.01" />
                            <path d="M12 14h.01" />
                            <path d="M8 14h.01" />
                            <path d="M12 18h.01" />
                            <path d="M8 18h.01" />
                        </svg>
                        Calculate
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Calculation Result Alert (shown inline) -->
    <div id="kalkulasiResultInline" class="mb-4" style="display: none;"></div>

    <!-- Raw Data Inspector -->
    <div class="card-shadcn mb-4">
        <div class="card-shadcn-header">
            <div class="flex-between">
                <h3 class="card-shadcn-title">Raw Data Inspector (SAP & TrakCare)</h3>
                <form action="" method="GET" class="d-flex align-items-center gap-2">
                    <label for="filter_date" class="form-label-shadcn mb-0">Period:</label>
                    <input type="date" name="filter_date" id="filter_date" class="form-control-shadcn"
                        value="{{ $filterDate ?? date('Y-m-d') }}" style="width: auto;">
                    <button type="submit" class="btn-shadcn btn-shadcn-primary icon-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3" />
                        </svg>
                        Filter
                    </button>
                </form>
            </div>
        </div>
        <div class="card-shadcn-body">
            @php
                $activeTab = request('active_tab', 'sap');
            @endphp
            <ul class="nav nav-tabs mb-3" id="rawDataTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $activeTab == 'sap' ? 'active' : '' }}" id="sap-tab" data-bs-toggle="tab"
                        data-bs-target="#sap" type="button" role="tab" aria-controls="sap"
                        aria-selected="{{ $activeTab == 'sap' ? 'true' : 'false' }}" style="color: var(--foreground);"
                        onclick="updateActiveTab('sap')">
                        Stock SAP <span
                            class="badge-shadcn badge-shadcn-secondary ms-2">{{ isset($stockSAPs) ? $stockSAPs->total() : 0 }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $activeTab == 'tc' ? 'active' : '' }}" id="tc-tab" data-bs-toggle="tab"
                        data-bs-target="#tc" type="button" role="tab" aria-controls="tc"
                        aria-selected="{{ $activeTab == 'tc' ? 'true' : 'false' }}" style="color: var(--foreground);"
                        onclick="updateActiveTab('tc')">
                        Stock TrakCare <span
                            class="badge-shadcn badge-shadcn-secondary ms-2">{{ isset($stockTCs) ? $stockTCs->total() : 0 }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $activeTab == 'calc' ? 'active' : '' }}" id="calc-tab" data-bs-toggle="tab"
                        data-bs-target="#calc" type="button" role="tab" aria-controls="calc"
                        aria-selected="{{ $activeTab == 'calc' ? 'true' : 'false' }}" style="color: var(--foreground);"
                        onclick="updateActiveTab('calc')">
                        Stock Differences (Calculated) <span
                            class="badge-shadcn badge-shadcn-secondary ms-2">{{ isset($stockCalculations) ? $stockCalculations->total() : 0 }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $activeTab == 'comp' ? 'active' : '' }}" id="comp-tab" data-bs-toggle="tab"
                        data-bs-target="#comp" type="button" role="tab" aria-controls="comp"
                        aria-selected="{{ $activeTab == 'comp' ? 'true' : 'false' }}" style="color: var(--foreground);"
                        onclick="updateActiveTab('comp')">
                        Comparison Results <span
                            class="badge-shadcn badge-shadcn-secondary ms-2">{{ isset($stockComparisons) ? $stockComparisons->total() : 0 }}</span>
                    </button>
                </li>
            </ul>
            <div class="tab-content" id="rawDataTabContent">
                <!-- SAP Tab -->
                <div class="tab-pane fade {{ $activeTab == 'sap' ? 'show active' : '' }}" id="sap" role="tabpanel"
                    aria-labelledby="sap-tab">
                    <div class="table-container-shadcn mb-3">
                        <table class="table-shadcn table-striped" style="width:100%" id="tableSAP">
                            <thead>
                                <tr>
                                    <th>Period</th>
                                    <th>Combine Code</th>
                                    <th>Material</th>
                                    <th>SLoc</th>
                                    <th>Batch</th>
                                    <th>Qty</th>
                                    <th>BUCode</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($stockSAPs))
                                    @foreach($stockSAPs as $item)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($item->Period_DateTime)->format('d/m/Y') }}</td>
                                            <td>{{ $item->Combine_Code }}</td>
                                            <td>{{ $item->Material_Code }}<br><small
                                                    class="text-muted">{{ $item->Material_Desc }}</small></td>
                                            <td>{{ $item->Storage_Loc }}</td>
                                            <td>{{ $item->Batch_No }}</td>
                                            <td class="fw-bold">{{ number_format($item->Qty, 2) }}</td>
                                            <td>{{ $item->BU_Code }}</td>
                                            <td>
                                                <span class="badge-shadcn badge-shadcn-outline">SAP</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-end">
                        {{ $stockSAPs->appends(['filter_date' => $filterDate, 'active_tab' => 'sap'])->links('pagination::bootstrap-5') }}
                    </div>
                </div>

                <!-- TrakCare Tab -->
                <div class="tab-pane fade {{ $activeTab == 'tc' ? 'show active' : '' }}" id="tc" role="tabpanel"
                    aria-labelledby="tc-tab">
                    <div class="table-container-shadcn mb-3">
                        <table class="table-shadcn table-striped" style="width:100%" id="tableTC">
                            <thead>
                                <tr>
                                    <th>Period</th>
                                    <th>Combine Code</th>
                                    <th>Material</th>
                                    <th>SLoc</th>
                                    <th>Batch</th>
                                    <th>Phy Qty</th>
                                    <th>UOM</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($stockTCs))
                                    @foreach($stockTCs as $item)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($item->Period_DateTime)->format('d/m/Y') }}</td>
                                            <td>{{ $item->Combine_Code }}</td>
                                            <td>{{ $item->INCLB_INCI_Code }}<br><small
                                                    class="text-muted">{{ $item->INCLB_INCI_Desc }}</small></td>
                                            <td>{{ $item->INCLB_CTLOC_Code }}<br><small
                                                    class="text-muted">{{ $item->INCLB_CTLOC_Desc }}</small></td>
                                            <td>{{ $item->INCLB_INCIB_No }}</td>
                                            <td class="fw-bold">{{ number_format($item->INCLB_PhyQty, 2) }}</td>
                                            <td>{{ $item->CTUOM_Code }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-end">
                        {{ $stockTCs->appends(['filter_date' => $filterDate, 'active_tab' => 'tc'])->links('pagination::bootstrap-5') }}
                    </div>
                </div>

                <!-- Calculated Stock Tab -->
                <div class="tab-pane fade {{ $activeTab == 'calc' ? 'show active' : '' }}" id="calc" role="tabpanel"
                    aria-labelledby="calc-tab">
                    <div class="table-container-shadcn mb-3">
                        <table class="table-shadcn table-striped" style="width:100%" id="tableCalc">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Combine Code</th>
                                    <th>Indicator</th>
                                    <th>Material</th>
                                    <th>SLoc</th>
                                    <th>Batch</th>
                                    <th>Qty</th>
                                    <th>UOM</th>
                                    <th>Created At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($stockCalculations))
                                    @foreach($stockCalculations as $item)
                                        <tr>
                                            <td>{{ $item->id }}</td>
                                            <td>{{ $item->Combine_Code }}</td>
                                            <td>
                                                <span
                                                    class="badge-shadcn {{ $item->indicator == 'P' ? 'badge-shadcn-success' : 'badge-shadcn-destructive' }}">
                                                    {{ $item->indicator }}
                                                </span>
                                            </td>
                                            <td>{{ $item->material }}</td>
                                            <td>{{ $item->sloc }}</td>
                                            <td>{{ $item->batch }}</td>
                                            <td class="fw-bold">{{ number_format($item->qty, 2) }}</td>
                                            <td>{{ $item->uom }}</td>
                                            <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-end">
                        {{ $stockCalculations->appends(['filter_date' => $filterDate, 'active_tab' => 'calc'])->links('pagination::bootstrap-5') }}
                    </div>
                </div>

                <!-- Comparison Tab -->
                <div class="tab-pane fade {{ $activeTab == 'comp' ? 'show active' : '' }}" id="comp" role="tabpanel"
                    aria-labelledby="comp-tab">
                    <div class="table-container-shadcn mb-3">
                        <table class="table-shadcn table-striped" style="width:100%" id="tableComp">
                            <thead>
                                <tr>
                                    <th>Period</th>
                                    <th>Combine Code</th>
                                    <th>Material</th>
                                    <th>SLoc</th>
                                    <th>Batch</th>
                                    <th>QTY SAP</th>
                                    <th>QTY TC</th>
                                    <th>BU Code</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($stockComparisons))
                                    @foreach($stockComparisons as $item)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($item->Period_DateTime)->format('d/m/Y') }}</td>
                                            <td>{{ $item->Combine_Code }}</td>
                                            <td>{{ $item->Material_Code }}<br><small
                                                    class="text-muted">{{ $item->Material_Desc }}</small></td>
                                            <td>{{ $item->Storage_Loc }}</td>
                                            <td>{{ $item->Batch_No }}</td>
                                            <td class="fw-bold text-success">{{ number_format($item->QTY_SAP, 2) }}</td>
                                            <td class="fw-bold text-info">{{ number_format($item->QTY_TC, 2) }}</td>
                                            <td>{{ $item->BU_Code }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-end">
                        {{ $stockComparisons->appends(['filter_date' => $filterDate, 'active_tab' => 'comp'])->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- Modal: Import Excel -->
    <div class="modal fade" id="modalImportExcel" tabindex="-1" aria-labelledby="modalImportExcelLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-content-shadcn">
                <div class="modal-header-shadcn">
                    <h5 class="modal-title">Import Excel File</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body-shadcn">
                        <div class="alert-shadcn alert-shadcn-info mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10" />
                                <path d="M12 16v-4" />
                                <path d="M12 8h.01" />
                            </svg>
                            <div class="alert-description">
                                <strong>Step 1:</strong> Import Excel file to Stock SAP or Stock TrakCare.
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="import_type" class="form-label-shadcn">Import Type</label>
                            <select name="import_type" class="form-select-shadcn" id="import_type" required>
                                <option value="">-- Select Import Type --</option>
                                <option value="sap">SAP (StockSAP)</option>
                                <option value="trakcare">TrakCare (StockTCINC_ItmLcBt)</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="file" class="form-label-shadcn">Choose Excel File (.xlsx, .xls, .csv)</label>
                            <input type="file" name="file" class="form-file-shadcn" id="file" required>
                        </div>

                        <!-- Progress Bar Container -->
                        <div id="importProgressContainer" class="d-none">
                            <div class="alert-shadcn alert-shadcn-info mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="spinner-border spinner-border-sm me-2" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <span id="importProgressMessage" class="fw-medium">Processing...</span>
                                </div>

                                <!-- Progress Bar -->
                                <div class="progress-shadcn mb-2">
                                    <div id="importProgressBar" class="progress-bar-shadcn" role="progressbar"
                                        style="width: 0%">
                                        <span id="importProgressText">0%</span>
                                    </div>
                                </div>

                                <!-- Stats -->
                                <div class="d-flex justify-content-between text-xs text-muted">
                                    <span id="importStats">0 / 0 records</span>
                                    <span id="importSpeed">0 rec/s</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer-shadcn">
                        <button type="button" class="btn-shadcn btn-shadcn-outline" data-bs-dismiss="modal"
                            id="btnCancelImport">Cancel</button>
                        <button type="submit" class="btn-shadcn btn-shadcn-primary" id="btnSubmitImport">
                            <span class="spinner-border spinner-border-sm d-none" id="spinnerImportExcel" role="status"
                                aria-hidden="true"></span>
                            Import File
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>



    <!-- Modal: Save Manual -->
    <div class="modal fade" id="modalSaveManual" tabindex="-1" aria-labelledby="modalSaveManualLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content modal-content-shadcn">
                <div class="modal-header-shadcn">
                    <h5 class="modal-title">Save Data Manually</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('save_manual') }}" method="POST">
                    @csrf
                    <div class="modal-body-shadcn">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="materialDocument" class="form-label-shadcn">Material Document *</label>
                                <input type="text" name="materialDocument" class="form-control-shadcn" required>
                            </div>
                            <div class="col-md-6">
                                <label for="materialDocumentYear" class="form-label-shadcn">Material Document Year</label>
                                <input type="text" name="materialDocumentYear" class="form-control-shadcn">
                            </div>
                            <div class="col-md-6">
                                <label for="plant" class="form-label-shadcn">Plant</label>
                                <input type="text" name="plant" class="form-control-shadcn">
                            </div>
                            <div class="col-md-6">
                                <label for="documentDate" class="form-label-shadcn">Document Date</label>
                                <input type="date" name="documentDate" class="form-control-shadcn">
                            </div>
                            <div class="col-md-6">
                                <label for="postingDate" class="form-label-shadcn">Posting Date</label>
                                <input type="date" name="postingDate" class="form-control-shadcn">
                            </div>
                            <div class="col-md-6">
                                <label for="goodMovementText" class="form-label-shadcn">Good Movement Text</label>
                                <input type="text" name="goodMovementText" class="form-control-shadcn">
                            </div>
                            <div class="col-md-6">
                                <label for="vendor" class="form-label-shadcn">Vendor</label>
                                <input type="text" name="vendor" class="form-control-shadcn">
                            </div>
                            <div class="col-md-6">
                                <label for="purchaseOrder" class="form-label-shadcn">Purchase Order</label>
                                <input type="text" name="purchaseOrder" class="form-control-shadcn">
                            </div>
                            <div class="col-md-6">
                                <label for="reservation" class="form-label-shadcn">Reservation</label>
                                <input type="text" name="reservation" class="form-control-shadcn">
                            </div>
                            <div class="col-md-6">
                                <label for="outboundDelivery" class="form-label-shadcn">Outbound Delivery</label>
                                <input type="text" name="outboundDelivery" class="form-control-shadcn">
                            </div>
                            <div class="col-md-6">
                                <label for="sapTransactionDate" class="form-label-shadcn">SAP Transaction Date</label>
                                <input type="date" name="sapTransactionDate" class="form-control-shadcn">
                            </div>
                            <div class="col-md-6">
                                <label for="sapTransactionTime" class="form-label-shadcn">SAP Transaction Time</label>
                                <input type="time" name="sapTransactionTime" class="form-control-shadcn">
                            </div>
                            <div class="col-md-6">
                                <label for="user" class="form-label-shadcn">User</label>
                                <input type="text" name="user" class="form-control-shadcn">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer-shadcn">
                        <button type="button" class="btn-shadcn btn-shadcn-outline" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn-shadcn btn-shadcn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal: Download JSON -->
    <div class="modal fade" id="modalDownloadJson" tabindex="-1" aria-labelledby="modalDownloadJsonLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-content-shadcn">
                <div class="modal-header-shadcn">
                    <h5 class="modal-title">Download JSON</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body-shadcn">
                    <form id="formDownloadJson">
                        <div class="mb-3">
                            <label for="formstock_id" class="form-label-shadcn">Select Material Document</label>
                            <select class="form-select-shadcn" id="formstock_id" name="formstock_id" required>
                                <option value="">-- Select Material Document --</option>
                                @foreach($formStocks ?? [] as $formStock)
                                    <option value="{{ $formStock->id }}" data-material-doc="{{ $formStock->materialDocument }}">
                                        {{ $formStock->materialDocument }}
                                        @if($formStock->materialDocumentYear)
                                            ({{ $formStock->materialDocumentYear }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer-shadcn">
                    <button type="button" class="btn-shadcn btn-shadcn-outline" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn-shadcn btn-shadcn-primary" id="btnDownloadJsonByMaterialDoc">
                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        Download JSON
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function () {
            // Set today's date on page load for inline datepicker
            const today = new Date().toISOString().split('T')[0];
            $('#period_date_inline').val(today);

            // Inline Calculate button handler
            $('#btnKalkulasiInline').click(function () {
                const btn = $(this);
                const spinner = $('#spinnerKalkulasiInline');
                const periodDate = $('#period_date_inline').val();

                if (!periodDate) {
                    $('#kalkulasiResultInline').show().html('<div class="alert-shadcn alert-shadcn-warning"><div class="alert-description">Please select a period date first.</div></div>');
                    return;
                }

                btn.prop('disabled', true);
                spinner.removeClass('d-none');

                $.ajax({
                    url: '{{ route("stock-management.kalkulasi") }}',
                    method: 'POST',
                    data: { period_date: periodDate },
                    success: function (response) {
                        $('#kalkulasiResultInline').show().html(`
                                            <div class="alert-shadcn alert-shadcn-success">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                                <div>
                                                    <div class="alert-title">${response.message}</div>
                                                    <div class="alert-description">
                                                        <div class="mt-2 mb-2"><strong>Period Date:</strong> ${response.data.period_date}</div>
                                                        <hr style="border-color: var(--border); margin: 0.5rem 0;">
                                                        <div class="row mt-2">
                                                            <div class="col-6">
                                                                <small><strong>SAP Data:</strong> ${response.data.total_sap_records.toLocaleString()}</small><br>
                                                                <small><strong>TrakCare Data:</strong> ${response.data.total_tc_records.toLocaleString()}</small><br>
                                                                <small><strong>Processed:</strong> ${response.data.total_processed.toLocaleString()}</small>
                                                            </div>
                                                            <div class="col-6">
                                                                <small><strong>Plus (P):</strong> <span class="badge-shadcn badge-shadcn-success">${response.data.plus_indicator.toLocaleString()}</span></small><br>
                                                                <small><strong>Minus (M):</strong> <span class="badge-shadcn badge-shadcn-destructive">${response.data.minus_indicator.toLocaleString()}</span></small><br>
                                                                <small><strong>Skipped:</strong> <span class="badge-shadcn badge-shadcn-secondary">${response.data.skipped_zero.toLocaleString()}</span></small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        `);
                        // Auto-hide after 10 seconds
                        setTimeout(() => { $('#kalkulasiResultInline').fadeOut(); }, 10000);

                        // Optional: Reload the page or update the Calculated tab if you want to see results immediately
                        // For now we just show the success message. Users can click 'Filter' on the inspector to see new data.
                    },
                    error: function (xhr) {
                        const message = xhr.responseJSON?.message || 'An error occurred during calculation';
                        $('#kalkulasiResultInline').show().html(`<div class="alert-shadcn alert-shadcn-destructive"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" x2="9" y1="9" y2="15"/><line x1="9" x2="15" y1="9" y2="15"/></svg><div class="alert-description">${message}</div></div>`);
                    },
                    complete: function () {
                        btn.prop('disabled', false);
                        spinner.addClass('d-none');
                    }
                });
            });

            // Inline Compare button handler
            $('#btnCompareInline').click(function () {
                const btn = $(this);
                const spinner = $('#spinnerCompareInline');
                const periodDate = $('#period_date_inline').val();

                if (!periodDate) {
                    $('#kalkulasiResultInline').show().html('<div class="alert-shadcn alert-shadcn-warning"><div class="alert-description">Please select a period date first.</div></div>');
                    return;
                }

                btn.prop('disabled', true);
                spinner.removeClass('d-none');

                $.ajax({
                    url: '{{ route("stock-management.compare") }}',
                    method: 'POST',
                    data: { period_date: periodDate, _token: '{{ csrf_token() }}' },
                    success: function (response) {
                        $('#kalkulasiResultInline').show().html(`
                                <div class="alert-shadcn alert-shadcn-success">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                    <div>
                                        <div class="alert-title">${response.message}</div>
                                        <div class="alert-description">
                                            <div class="mt-2 mb-2"><strong>Period Date:</strong> ${response.data.period_date}</div>
                                            <hr style="border-color: var(--border); margin: 0.5rem 0;">
                                            <div class="mt-2">
                                                <small><strong>Processed:</strong> ${response.data.total_processed.toLocaleString()}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `);
                        // Auto-hide after 10 seconds
                        setTimeout(() => { $('#kalkulasiResultInline').fadeOut(); }, 10000);
                    },
                    error: function (xhr) {
                        const message = xhr.responseJSON?.message || 'An error occurred during comparison';
                        $('#kalkulasiResultInline').show().html(`<div class="alert-shadcn alert-shadcn-destructive"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" x2="9" y1="9" y2="15"/><line x1="9" x2="15" y1="9" y2="15"/></svg><div class="alert-description">${message}</div></div>`);
                    },
                    complete: function () {
                        btn.prop('disabled', false);
                        spinner.addClass('d-none');
                    }
                });
            });

            $('#btnDownloadJsonByMaterialDoc').click(function () {
                const btn = $(this);
                const spinner = btn.find('.spinner-border');
                const formstockId = $('#formstock_id').val();
                const materialDoc = $('#formstock_id option:selected').data('material-doc');

                if (!formstockId || !materialDoc) {
                    showAlert('Please select a Material Document first!', 'error');
                    return;
                }

                btn.prop('disabled', true);
                spinner.removeClass('d-none');

                const form = $('<form>', { 'method': 'POST', 'action': '{{ route("stock-management.download-json") }}' });
                form.append($('<input>', { 'type': 'hidden', 'name': '_token', 'value': '{{ csrf_token() }}' }));
                form.append($('<input>', { 'type': 'hidden', 'name': 'materialDocument', 'value': materialDoc }));
                $('body').append(form);
                form.submit();
                form.remove();

                setTimeout(function () {
                    $('#modalDownloadJson').modal('hide');
                    btn.prop('disabled', false);
                    spinner.addClass('d-none');
                    $('#formDownloadJson')[0].reset();
                    showAlert('JSON file is being downloaded...', 'success');
                }, 500);
            });

            $('#modalDownloadJson').on('hidden.bs.modal', function () {
                $('#formDownloadJson')[0].reset();
                $('#btnDownloadJsonByMaterialDoc').prop('disabled', false);
                $('#btnDownloadJsonByMaterialDoc .spinner-border').addClass('d-none');
            });




            // Import with Progress Bar
            let progressInterval = null;
            let sessionId = '{{ session()->getId() }}';
            let startTime = null;

            $('#modalImportExcel form').on('submit', function (e) {
                e.preventDefault();

                const form = $(this);
                const formData = new FormData(form[0]);
                formData.append('session_id', sessionId);

                // Show progress container
                $('#importProgressContainer').removeClass('d-none');
                $('#btnSubmitImport').prop('disabled', true);
                $('#btnCancelImport').prop('disabled', true);
                $('#spinnerImportExcel').removeClass('d-none');

                // Reset progress
                updateProgressUI(0, 0, 0, 'Initializing...');
                startTime = Date.now();

                // Start progress polling
                startProgressPolling();

                // Submit via AJAX
                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    success: function (response) {
                        stopProgressPolling();

                        if (response.success) {
                            updateProgressUI(response.imported, response.imported, 100, response.message);

                            setTimeout(function () {
                                $('#modalImportExcel').modal('hide');
                                showAlert(response.message, 'success');
                                location.reload();
                            }, 1500);
                        } else {
                            showAlert(response.message, 'error');
                            resetImportForm();
                        }
                    },
                    error: function (xhr) {
                        stopProgressPolling();
                        const message = xhr.responseJSON?.message || 'Error during import';
                        showAlert(message, 'error');
                        resetImportForm();
                    }
                });
            });

            function startProgressPolling() {
                progressInterval = setInterval(function () {
                    $.get('{{ route("import.progress") }}', { session_id: sessionId }, function (progress) {
                        if (progress && progress.percentage !== undefined) {
                            updateProgressUI(
                                progress.imported,
                                progress.total,
                                progress.percentage,
                                progress.message
                            );
                        }
                    });
                }, 500); // Poll every 500ms
            }

            function stopProgressPolling() {
                if (progressInterval) {
                    clearInterval(progressInterval);
                    progressInterval = null;
                }
            }

            function updateProgressUI(imported, total, percentage, message) {
                $('#importProgressBar').css('width', percentage + '%');
                $('#importProgressText').text(Math.round(percentage) + '%');
                $('#importProgressMessage').text(message);
                $('#importStats').text(imported + ' / ' + total + ' records');

                // Calculate speed
                if (startTime && imported > 0) {
                    const elapsed = (Date.now() - startTime) / 1000; // seconds
                    const speed = Math.round(imported / elapsed);
                    $('#importSpeed').text(speed + ' rec/s');
                }
            }

            function resetImportForm() {
                $('#importProgressContainer').addClass('d-none');
                $('#btnSubmitImport').prop('disabled', false);
                $('#btnCancelImport').prop('disabled', false);
                $('#spinnerImportExcel').addClass('d-none');
                updateProgressUI(0, 0, 0, 'Initializing...');
            }

            // Reset form when modal is closed
            $('#modalImportExcel').on('hidden.bs.modal', function () {
                stopProgressPolling();
                resetImportForm();
                $('#modalImportExcel form')[0].reset();

                // Clear progress on server
                $.post('{{ route("import.clear-progress") }}', {
                    _token: '{{ csrf_token() }}',
                    session_id: sessionId
                });
            });

            function showAlert(message, type) {
                const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
                const alertHtml = `
                                <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                                    ${message}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            `;
                $('.container-fluid').prepend(alertHtml);

                setTimeout(function () {
                    $('.alert').fadeOut(function () {
                        $(this).remove();
                    });
                }, 5000);
            }
        }); // Close $(document).ready
    </script>
@endpush