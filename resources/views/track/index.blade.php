@extends('layouts.app')

@section('title', 'AR Tracking - Bali International Hospital')

@push('styles')
    <style>
        /* ===== Filter Header ===== */
        .filter-header {
            background-color: var(--card);
            padding: 1.5rem;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            margin-bottom: 1.5rem;
            border: 1px solid var(--border);
        }

        /* ===== Status Tabs ===== */
        .status-tabs {
            display: flex;
            flex-wrap: wrap;
            gap: 0.25rem;
            padding-bottom: 0;
            margin-bottom: 1rem;
            border-bottom: 2px solid var(--border);
        }

        .status-tab {
            padding: 0.6rem 1rem;
            border: none;
            background: none;
            color: var(--muted-foreground);
            font-weight: 500;
            font-size: 0.8125rem;
            cursor: pointer;
            white-space: nowrap;
            position: relative;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .status-tab:hover {
            color: var(--foreground);
            background-color: var(--accent);
        }

        .status-tab.active {
            color: var(--brand);
            font-weight: 600;
        }

        .status-tab.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            right: 0;
            height: 2px;
            background-color: var(--brand);
            border-radius: 2px 2px 0 0;
        }

        .tab-count {
            font-size: 0.7rem;
            font-weight: 700;
            padding: 0.1rem 0.45rem;
            border-radius: 9999px;
            background-color: var(--muted);
            color: var(--muted-foreground);
            min-width: 1.3rem;
            text-align: center;
        }

        .status-tab.active .tab-count {
            background-color: var(--brand);
            color: #fff;
        }

        /* ===== Data Grid ===== */
        .grid-container {
            background-color: var(--card);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            overflow: hidden;
            margin-bottom: 2rem;
            border: 1px solid var(--border);
        }

        /* ===== Table ===== */
        .table-container {
            width: 100%;
            overflow-x: auto;
            overflow-y: auto;
            max-height: 80vh;
            -webkit-overflow-scrolling: touch;
        }

        .table-shadcn {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            min-width: 1200px;
        }

        .table-shadcn th,
        .table-shadcn td {
            padding: 0.25rem 0.5rem;
            border-bottom: 1px solid var(--border);
            white-space: nowrap;
            color: var(--foreground);
            font-size: 0.75rem;
            line-height: 1.3;
        }

        .table-shadcn th {
            font-weight: 600;
            font-size: 0.7rem;
            padding-top: 0.35rem;
            padding-bottom: 0.35rem;
            color: var(--muted-foreground);
            background-color: var(--muted);
            position: sticky;
            top: 0;
            z-index: 4;
        }

        .table-shadcn tbody tr:hover {
            background-color: var(--muted);
            cursor: pointer;
        }

        /* ===== Badge Status ===== */
        .badge-status {
            padding: 0.25rem 0.6rem;
            border-radius: 9999px;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
        }

        /* BATCHING = default / abu muda */
        .badge-status.BATCHING { background-color: var(--muted); color: var(--muted-foreground); border: 1px solid var(--border); }
        /* SENT = biru */
        .badge-status.SENT { background-color: #dbeafe; color: #1d4ed8; border: 1px solid #93c5fd; }
        /* RECEIVED = abu gelap */
        .badge-status.RECEIVED { background-color: var(--secondary); color: var(--secondary-foreground); border: 1px solid var(--border); }
        /* REVISE = merah */
        .badge-status.REVISE { background-color: #fee2e2; color: #dc2626; border: 1px solid #fca5a5; }
        /* PAID = hijau */
        .badge-status.PAID { background-color: #dcfce7; color: #15803d; border: 1px solid #86efac; }

        .dark .badge-status.SENT { background-color: rgba(29,78,216,0.2); color: #93c5fd; border-color: rgba(29,78,216,0.4); }
        .dark .badge-status.REVISE { background-color: rgba(220,38,38,0.2); color: #fca5a5; border-color: rgba(220,38,38,0.4); }
        .dark .badge-status.PAID { background-color: rgba(21,128,61,0.2); color: #86efac; border-color: rgba(21,128,61,0.4); }

        /* ===== Cancel Flag ===== */
        .cancel-flag {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.15rem 0.5rem;
            border-radius: 9999px;
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            background-color: #fef2f2;
            color: #b91c1c;
            border: 1px solid #fecaca;
        }

        .dark .cancel-flag {
            background-color: rgba(220,38,38,0.15);
            color: #fca5a5;
            border-color: rgba(220,38,38,0.3);
        }

        .cancel-source-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.1rem 0.4rem;
            border-radius: 9999px;
            font-size: 0.58rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            background-color: #fef3c7;
            color: #92400e;
            border: 1px solid #fcd34d;
        }

        .dark .cancel-source-badge {
            background-color: rgba(245, 158, 11, 0.18);
            color: #fcd34d;
            border-color: rgba(245, 158, 11, 0.35);
        }

        .row-cancelled {
            background-color: rgba(220, 38, 38, 0.08) !important;
        }

        .dark .row-cancelled {
            background-color: rgba(220, 38, 38, 0.12) !important;
        }

        .row-cancelled td {
            color: #b91c1c !important;
        }

        .dark .row-cancelled td {
            color: #fca5a5 !important;
        }

        .row-cancelled .sticky-col {
            background-color: #fde8e8 !important;
        }

        .dark .row-cancelled .sticky-col {
            background-color: #3b1515 !important;
        }

        /* ===== Dropdown dark mode ===== */
        .dark .dropdown-menu {
            background-color: var(--popover);
            border-color: var(--border);
        }
        .dark .dropdown-item {
            color: var(--popover-foreground);
        }
        .dark .dropdown-item:hover {
            background-color: var(--accent);
            color: var(--accent-foreground);
        }
        .dark .dropdown-divider {
            border-color: var(--border);
        }

        /* ===== Dropdown Action Items ===== */
        .dropdown-item.action-dd-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.8rem;
            padding: 0.45rem 0.75rem;
            transition: all 0.15s;
        }

        .dropdown-item.action-dd-item .action-icon {
            width: 16px;
            height: 16px;
            flex-shrink: 0;
            opacity: 0.8;
        }

        .dropdown-item.action-dd-item.action-primary {
            font-weight: 600;
            color: #15803d;
        }
        .dark .dropdown-item.action-dd-item.action-primary {
            color: #86efac;
        }

        .dropdown-item.action-dd-item.disabled,
        .dropdown-item.action-dd-item[aria-disabled="true"] {
            opacity: 0.35;
            pointer-events: none;
            cursor: not-allowed;
            color: var(--muted-foreground) !important;
        }

        .dropdown-item.action-dd-item .action-badge {
            font-size: 0.6rem;
            padding: 0.1rem 0.35rem;
            border-radius: 4px;
            margin-left: auto;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }

        .action-badge.badge-next {
            background-color: #dbeafe;
            color: #1d4ed8;
        }
        .dark .action-badge.badge-next {
            background-color: rgba(29,78,216,0.2);
            color: #93c5fd;
        }

        .hidden-col { display: none; }

        /* ===== Sticky Columns ===== */
        .sticky-col {
            position: sticky !important;
            background-color: var(--muted);
            z-index: 2;
            background-clip: padding-box;
        }

        tbody .sticky-col {
            background-color: var(--card);
        }

        tbody tr:hover .sticky-col {
            background-color: var(--muted);
        }

        thead .sticky-col {
            background-color: var(--muted) !important;
        }

        /* Right-side shadow on last sticky column to cover scroll bleed */
        .sticky-status::after {
            content: '';
            position: absolute;
            top: 0;
            right: -3px;
            bottom: 0;
            width: 3px;
            background: linear-gradient(to right, rgba(0,0,0,0.06), transparent);
            pointer-events: none;
        }

        /* ===== Summary Footer ===== */
        tfoot {
            position: sticky;
            bottom: 0;
            z-index: 3;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.05);
        }

        tfoot td {
            background-color: var(--muted) !important;
            border-top: 2px solid var(--border) !important;
            border-bottom: none !important;
            padding-top: 0.5rem;
            padding-bottom: 0.5rem;
            font-weight: 600;
        }

        tfoot .sticky-col {
            z-index: 5 !important;
        }

        .sticky-batch { width: 110px; min-width: 110px; max-width: 110px; white-space: normal; left: 0px !important; }
        .sticky-invno { width: 120px; min-width: 120px; max-width: 120px; white-space: normal; left: 110px !important; }
        .sticky-adm { width: 120px; min-width: 120px; max-width: 120px; white-space: normal; left: 230px !important; }
        .sticky-pat { width: 160px; min-width: 160px; max-width: 160px; white-space: normal !important; word-break: break-word; left: 350px !important; }
        .sticky-ref { width: 80px; min-width: 80px; max-width: 80px; white-space: normal; left: 510px !important; }
        .sticky-status { width: 75px; min-width: 75px; max-width: 75px; white-space: normal !important; text-align: center; border-right: 2px solid var(--border) !important; left: 590px !important; }

        /* ===== Modal Dark Mode ===== */
        .dark .modal-content {
            background-color: var(--card);
            color: var(--foreground);
            border: 1px solid var(--border);
        }
        .dark .modal-header, .dark .modal-footer {
            border-color: var(--border);
        }
        .dark .form-control, .dark .form-select {
            background-color: var(--background);
            color: var(--foreground);
            border-color: var(--input);
        }
        .dark .form-control::placeholder,
        .dark .form-select::placeholder {
            color: var(--muted-foreground);
            opacity: 1;
        }
        .dark .form-control:focus, .dark .form-select:focus {
            background-color: var(--background);
            border-color: var(--brand);
            color: var(--foreground);
        }
        .dark .btn-close {
            filter: invert(1) grayscale(100%) brightness(200%);
        }

        /* ===== Bootstrap overrides for dark mode ===== */
        .dark .btn-outline-secondary {
            color: var(--foreground);
            border-color: var(--input);
        }
        .dark .btn-outline-secondary:hover {
            background-color: var(--accent);
            color: var(--accent-foreground);
            border-color: var(--input);
        }
        .dark .form-label {
            color: var(--foreground) !important;
        }
        .dark .border-top {
            border-top-color: var(--border) !important;
        }
        .dark .input-group-text {
            background-color: var(--muted);
            border-color: var(--input);
            color: var(--muted-foreground);
        }

        .action-modal-summary {
            padding: 0.8rem 0.9rem;
            border: 1px solid var(--border);
            border-radius: 1rem;
            background:
                linear-gradient(180deg, rgba(255,255,255,0.02), rgba(255,255,255,0)),
                var(--muted);
            margin-bottom: 0.75rem;
        }

        .action-modal-btn {
            width: 100%;
            justify-content: flex-start;
            text-align: left;
            margin-bottom: 0;
            min-height: 2.45rem;
            border-radius: 0.75rem;
            font-weight: 600;
            font-size: 0.85rem;
        }

        .action-modal-btn.action-sent {
            background-color: #dbeafe;
            color: #1d4ed8;
            border-color: #93c5fd;
        }

        .action-modal-btn.action-sent:hover {
            background-color: #bfdbfe;
            color: #1d4ed8;
            border-color: #60a5fa;
        }

        .action-modal-btn.action-received {
            background-color: #e5e7eb;
            color: #374151;
            border-color: #d1d5db;
        }

        .action-modal-btn.action-received:hover {
            background-color: #d1d5db;
            color: #1f2937;
            border-color: #9ca3af;
        }

        .action-modal-btn.action-revise {
            background-color: #fee2e2;
            color: #dc2626;
            border-color: #fca5a5;
        }

        .action-modal-btn.action-revise:hover {
            background-color: #fecaca;
            color: #b91c1c;
            border-color: #f87171;
        }

        .action-modal-btn.action-paid {
            background-color: #dcfce7;
            color: #15803d;
            border-color: #86efac;
        }

        .action-modal-btn.action-paid:hover {
            background-color: #bbf7d0;
            color: #166534;
            border-color: #4ade80;
        }

        .action-modal-btn:last-child {
            margin-bottom: 0;
        }

        .action-modal-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0.45rem;
        }

        .action-modal-btn.action-danger {
            background-color: #dc2626;
            color: #fff;
            border-color: #dc2626;
            grid-column: 1 / -1;
        }

        .action-modal-btn.action-danger:hover {
            background-color: #b91c1c;
            border-color: #b91c1c;
            color: #fff;
        }

        .action-meta-label {
            font-size: 0.62rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            font-weight: 700;
            color: var(--muted-foreground);
            margin-bottom: 0.15rem;
        }

        .action-meta-value {
            font-size: 0.84rem;
            color: var(--foreground);
            font-weight: 600;
            word-break: break-word;
        }

        .action-modal-statusline {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.55rem;
            margin-top: 0.65rem;
            padding-top: 0.65rem;
            border-top: 1px dashed var(--border);
            flex-wrap: wrap;
        }

        .action-modal-subtitle {
            font-size: 0.76rem;
            color: var(--muted-foreground);
            margin: 0 0 0.55rem;
        }

        .action-modal-section-title {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--muted-foreground);
            font-weight: 700;
            margin-bottom: 0.45rem;
        }

        .action-modal-dialog {
            max-width: 560px;
        }

        .action-modal-content {
            border-radius: 1.1rem;
            border: 1px solid var(--border);
            box-shadow: 0 18px 50px rgba(15, 23, 42, 0.14);
            overflow: hidden;
        }

        .dark .action-modal-content {
            box-shadow: 0 18px 50px rgba(0, 0, 0, 0.38);
        }

        .dark .action-modal-btn.action-sent {
            background-color: rgba(29, 78, 216, 0.2);
            color: #93c5fd;
            border-color: rgba(147, 197, 253, 0.3);
        }

        .dark .action-modal-btn.action-received {
            background-color: rgba(148, 163, 184, 0.16);
            color: #e5e7eb;
            border-color: rgba(203, 213, 225, 0.2);
        }

        .dark .action-modal-btn.action-revise {
            background-color: rgba(220, 38, 38, 0.2);
            color: #fca5a5;
            border-color: rgba(252, 165, 165, 0.28);
        }

        .dark .action-modal-btn.action-paid {
            background-color: rgba(21, 128, 61, 0.22);
            color: #86efac;
            border-color: rgba(134, 239, 172, 0.28);
        }

        .action-modal-header {
            padding-bottom: 0.15rem !important;
        }

        @media (max-width: 767.98px) {
            .action-modal-grid {
                grid-template-columns: 1fr;
            }
        }

        .modal.fade .modal-dialog {
            transition-duration: 0.12s;
        }

        .modal-backdrop.fade {
            transition-duration: 0.12s;
        }

        .table-controls {
            padding: 0.6rem 0.9rem !important;
        }

        .table-controls .form-select-sm {
            padding-top: 0.2rem;
            padding-bottom: 0.2rem;
            min-height: auto;
        }

        .table-controls .btn {
            padding-top: 0.2rem;
            padding-bottom: 0.2rem;
            line-height: 1.2;
        }

        .table-controls .text-sm,
        .table-controls label,
        .table-controls span {
            font-size: 0.8rem;
            line-height: 1.2;
        }
    </style>
@endpush

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0" style="color: var(--foreground); font-weight: 700;">AR Tracking</h1>
            <p class="text-muted">Monitor accounts receivable document statuses.</p>
        </div>
    </div>

    <div class="filter-header">
        <div class="row g-3">
            <div class="col-md-2">
                <label for="batchDateFrom" class="form-label" style="font-size: 0.875rem; font-weight: 500; color: var(--foreground);">Batch Date From</label>
                <input type="date" id="batchDateFrom" class="form-control" style="background-color: var(--card); border-color: var(--input); color: var(--foreground);">
            </div>
            <div class="col-md-2">
                <label for="batchDateTo" class="form-label" style="font-size: 0.875rem; font-weight: 500; color: var(--foreground);">Batch Date To</label>
                <input type="date" id="batchDateTo" class="form-control" style="background-color: var(--card); border-color: var(--input); color: var(--foreground);">
            </div>
            <div class="col-md-2">
                <label for="invDateFrom" class="form-label" style="font-size: 0.875rem; font-weight: 500; color: var(--foreground);">Invoice Printed From</label>
                <input type="date" id="invDateFrom" class="form-control" style="background-color: var(--card); border-color: var(--input); color: var(--foreground);">
            </div>
            <div class="col-md-2">
                <label for="invDateTo" class="form-label" style="font-size: 0.875rem; font-weight: 500; color: var(--foreground);">Invoice Printed To</label>
                <input type="date" id="invDateTo" class="form-control" style="background-color: var(--card); border-color: var(--input); color: var(--foreground);">
            </div>
            <div class="col-md-2">
                <label for="payerFilter" class="form-label" style="font-size: 0.875rem; font-weight: 500; color: var(--foreground);">Payer</label>
                <select id="payerFilter" class="form-select" style="background-color: var(--card); border-color: var(--input); color: var(--foreground);">
                    <option value="All">All Payers</option>
                    <!-- Dynamically populated from data -->
                </select>
            </div>
            <div class="col-md-2">
                <label for="searchFilter" class="form-label" style="font-size: 0.875rem; font-weight: 500; color: var(--foreground);">Search</label>
                <div class="input-group">
                    <span class="input-group-text" style="background-color: var(--muted); border-color: var(--input); color: var(--muted-foreground);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    </span>
                    <input type="text" id="searchFilter" class="form-control" placeholder="Patient, MRN, Inv No..." style="background-color: var(--card); border-color: var(--input); color: var(--foreground);">
                </div>
            </div>
        </div>
        <div class="row g-3 mt-1">
            <div class="col-md-12 d-flex justify-content-end gap-2">
                 <button class="btn-shadcn btn-shadcn-outline" id="resetBtn" style="min-width: 100px;">
                     Reset
                 </button>
                 <button class="btn-shadcn btn-shadcn-primary" id="btnExportExcel" style="min-width: 100px;">
                     Export
                 </button>
            </div>
        </div>
    </div>

    <!-- Status Tabs -->
    <div class="status-tabs" id="statusTabs">
        <!-- JS will populate tabs -->
    </div>

    <!-- Data Grid -->
    <div class="grid-container">
        <div class="table-container">
            <table class="table-shadcn" id="dataTable">
                <thead>
                    <tr>
                        <th class="sticky-col sticky-batch" rowspan="2" style="z-index: 5;">Batch Info</th>
                        <th class="sticky-col sticky-invno" rowspan="2" style="z-index: 5;">Invoice Info</th>
                        <th class="sticky-col sticky-adm" rowspan="2" style="z-index: 5;">Admission Info</th>
                        <th class="sticky-col sticky-pat" rowspan="2" style="z-index: 5;">Patient & Payer</th>
                        <th class="sticky-col sticky-ref" rowspan="2" style="z-index: 5;">Ref No</th>
                        <th class="sticky-col sticky-status" rowspan="2" style="z-index: 5;">Status</th>
                        <th colspan="10" class="text-center" style="border-bottom: 1px solid var(--border); border-right: 2px solid var(--border) !important;">💰 Section: Tracking</th>
                        <th rowspan="2">Remarks</th>
                        <th rowspan="2">Action</th>
                    </tr>
                    <tr>
                        <th>Before Discount</th>
                        <th>After Discount</th>
                        <th>Paid</th>
                        <th>Balance</th>
                        <th>Sent Date</th>
                        <th>VIA</th>
                        <th>Tracking#</th>
                        <th>Rcvd Date</th>
                        <th>Paid On</th>
                        <th style="border-right: 2px solid var(--border) !important;">Due Days</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <!-- JS will populate rows -->
                </tbody>
                <tfoot id="tableFooter">
                    <tr>
                        <td class="sticky-col sticky-batch text-center" id="tfCount">0</td>
                        <td class="sticky-col sticky-invno"></td>
                        <td class="sticky-col sticky-adm"></td>
                        <td class="sticky-col sticky-pat text-end" style="padding-right: 1.5rem;">Grand Total</td>
                        <td class="sticky-col sticky-ref"></td>
                        <td class="sticky-col sticky-status"></td>
                        <td class="text-end" id="tfBefore">0</td>
                        <td class="text-end" id="tfAfter">0</td>
                        <td class="text-end" id="tfPaid">0</td>
                        <td class="text-end" style="color: #059669;" id="tfBalance">0</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td style="border-right: 2px solid var(--border) !important;"></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="table-controls d-flex justify-content-between align-items-center border-top flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2">
                <label class="text-sm" style="color: var(--muted-foreground); white-space: nowrap;">Show</label>
                <select id="pageSizeSelect" class="form-select form-select-sm" style="width: auto; background-color: var(--card); border-color: var(--input); color: var(--foreground);">
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                    <option value="all" selected>All</option>
                </select>
                <span class="text-sm" style="color: var(--muted-foreground); white-space: nowrap;" id="recordCount">Showing 0 records</span>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="text-sm font-weight-bold" style="color: var(--foreground);" id="totalAmount">Total Balance: 0</span>
                <button class="btn btn-sm btn-outline-secondary" id="btnPrevPage" disabled>&laquo; Prev</button>
                <span class="text-sm" style="color: var(--muted-foreground);" id="pageInfo">Page 1 of 1</span>
                <button class="btn btn-sm btn-outline-secondary" id="btnNextPage" disabled>Next &raquo;</button>
            </div>
        </div>
    </div>

    <!-- Modals -->
    <!-- Send Document Modal -->
    <div class="modal fade" id="sendDocModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title font-weight-bold">Send Document</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small mb-3">You are dispatching <span id="sdItemCount">0</span> invoice(s) — Status will be set to <strong>SENT</strong>.</p>
                    <div class="mb-3">
                        <label class="form-label font-weight-bold small">Ref Number / Case Number</label>
                        <input type="text" class="form-control" id="inputRefNo" placeholder="e.g. 25-JKT001">
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-weight-bold small">Courier VIA</label>
                        <select class="form-select" id="inputVia" required>
                            <option value="JNE">JNE</option>
                            <option value="EMAIL">EMAIL</option>
                            <option value="PORTAL">PORTAL</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-weight-bold small">Tracking Number / Resi</label>
                        <input type="text" class="form-control" id="inputTrackingNo" placeholder="Enter AWB or Tracking Number" required>
                    </div>
                     <div class="mb-3">
                        <label class="form-label font-weight-bold small">Sent Date</label>
                        <input type="date" class="form-control" id="inputSentDate" value="{{ date('Y-m-d') }}" required>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn-shadcn btn-shadcn-ghost" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn-shadcn" style="background-color: #1d4ed8; color: white;" id="btnSubmitSendDoc">Update to SENT</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Set Received Modal -->
    <div class="modal fade" id="setReceivedModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title font-weight-bold">Document Received</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small mb-3">Mark <span id="rcItemCount">0</span> invoice(s) as <strong>RECEIVED</strong> by the Payer.</p>
                    <div class="mb-3">
                        <label class="form-label font-weight-bold small">Received Date</label>
                        <input type="date" class="form-control" id="inputReceivedDate" value="{{ date('Y-m-d') }}" required>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn-shadcn btn-shadcn-ghost" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn-shadcn" style="background-color: #334155; color: white;" id="btnSubmitReceived">Update to RECEIVED</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Remarks Modal -->
    <div class="modal fade" id="remarksModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title font-weight-bold">Update Remarks</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small mb-3">Update remarks for <span id="rmItemCount">0</span> invoice(s).</p>
                    <div class="mb-3">
                        <label class="form-label font-weight-bold small">Remarks</label>
                        <textarea class="form-control" id="inputRemarks" rows="3" placeholder="Enter custom note or update..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn-shadcn btn-shadcn-ghost" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn-shadcn btn-shadcn-primary" id="btnSubmitRemarks">Save Remarks</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="reviseModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title font-weight-bold" style="color: #dc2626;">Revise Invoice</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small mb-3">Mark <span id="rvItemCount">0</span> invoice(s) as <strong>REVISE</strong> because the payer returned the invoice.</p>
                    <div class="mb-3">
                        <label class="form-label font-weight-bold small">Revise Reason</label>
                        <textarea class="form-control" id="inputReviseReason" rows="3" placeholder="Reason from insurance..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn-shadcn btn-shadcn-ghost" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn-shadcn" style="background-color: #dc2626; color: white;" id="btnSubmitRevise">Update to REVISE</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Cancel Invoice Modal -->
    <div class="modal fade" id="cancelInvModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title font-weight-bold" style="color: #dc2626;">Cancel Invoice</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="p-3 mb-3" style="background-color: #fef2f2; border: 1px solid #fecaca; border-radius: 0.5rem;">
                        <p class="small mb-0" style="color: #991b1b;">
                            <strong>⚠️ Warning:</strong> You are about to cancel <span id="ccItemCount">1</span> invoice(s). 
                            The balance will remain until the <strong>5th of the following month</strong>, then automatically be set to 0.
                        </p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-weight-bold small">Cancellation Reason</label>
                        <textarea class="form-control" id="inputCancelReason" rows="2" placeholder="Reason for cancellation..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn-shadcn btn-shadcn-ghost" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn-shadcn" style="background-color: #dc2626; color: white;" id="btnSubmitCancel">Confirm Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="rowActionModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered action-modal-dialog">
            <div class="modal-content action-modal-content">
                <div class="modal-header border-bottom-0 pb-0 action-modal-header">
                    <div>
                        <h5 class="modal-title font-weight-bold">Invoice Actions</h5>
                        <div id="rowActionMeta" class="small text-muted"></div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="action-modal-summary">
                        <div class="action-meta-label">Patient</div>
                        <div id="rowActionPatient" class="action-meta-value"></div>
                        <div class="action-meta-label mt-3">Payer</div>
                        <div id="rowActionPayer" class="action-meta-value"></div>
                        <div class="action-modal-statusline">
                            <div>
                                <div class="action-meta-label mb-1">Current Status</div>
                                <div id="rowActionStatus" class="action-meta-value"></div>
                            </div>
                            <div id="rowActionCancelInfo"></div>
                        </div>
                    </div>
                    <p class="action-modal-subtitle">Pilih langkah berikutnya untuk invoice ini.</p>
                    <div class="action-modal-section-title">Available Actions</div>
                    <div id="rowActionButtons" class="action-modal-grid"></div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<!-- SheetJS Library for Excel Export -->
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
<script>
    // ===========================
    //  DATA
    // ===========================
    let invoicesData = @json($invoices);
    let activeTab = 'ALL';
    let selectedIds = new Set();
    let currentPage = 1;
    let pageSize = 'all';

    // ===========================
    //  TABS DEFINITION
    // ===========================
    const tabsDef = [
        { key: 'ALL',       label: 'All',       query: (item) => true },
        { key: 'BATCHING',  label: 'Batching',  query: (item) => item.status === 'BATCHING' && !item.is_cancelled },
        { key: 'SENT',      label: 'Sent',      query: (item) => item.status === 'SENT' && !item.is_cancelled },
        { key: 'RECEIVED',  label: 'Received',  query: (item) => item.status === 'RECEIVED' && !item.is_cancelled },
        { key: 'REVISE',    label: 'Revise',    query: (item) => item.status === 'REVISE' && !item.is_cancelled },
        { key: 'PAID',      label: 'Paid',      query: (item) => item.status === 'PAID' && !item.is_cancelled },
        { key: 'CANCELLED', label: 'Cancelled', query: (item) => item.is_cancelled === true },
    ];

    // ===========================
    //  HELPERS
    // ===========================
    const formatCurrency = (amount) => {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(amount);
    };

    const formatDate = (dateString) => {
        if (!dateString) return '-';
        const date = new Date(dateString);
        return date.toLocaleDateString('en-GB', { day: '2-digit', month: '2-digit', year: 'numeric' });
    };

    const getCsrfToken = () => document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    const mergeUpdatedItem = (updatedItem) => {
        invoicesData = invoicesData.map(inv => inv.invoice_no === updatedItem.invoice_no ? updatedItem : inv);
        updateUI();
    };

    const persistTrackingUpdate = async (payload) => {
        const response = await fetch('{{ route('track.update') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken(),
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(payload)
        });

        const data = await response.json();
        if (!response.ok) {
            const message = data.message || 'Failed to save tracking update.';
            throw new Error(message);
        }

        if (!data.item) {
            throw new Error('Server response did not include updated tracking data.');
        }

        mergeUpdatedItem(data.item);
        return data;
    };

    // Check if a cancelled invoice should have balance zeroed (past 5th of next month from cancel date)
    const shouldZeroBalance = (item) => {
        if (!item.is_cancelled || !item.cancelled_date) return false;
        const cancelDate = new Date(item.cancelled_date);
        const zeroMonth = cancelDate.getMonth() + 1; // next month
        const zeroYear = cancelDate.getFullYear() + (zeroMonth > 11 ? 1 : 0);
        const zeroDate = new Date(zeroYear, zeroMonth % 12, 5); // 5th of next month
        return new Date() >= zeroDate;
    };

    const getAutoZeroDate = (item) => {
        if (!item.is_cancelled || !item.cancelled_date) return null;
        const cancelDate = new Date(item.cancelled_date);
        const zeroMonth = cancelDate.getMonth() + 1;
        const zeroYear = cancelDate.getFullYear() + (zeroMonth > 11 ? 1 : 0);
        return new Date(zeroYear, zeroMonth % 12, 5);
    };

    // ===========================
    //  DOM REFS
    // ===========================
    const tableBody = document.getElementById('tableBody');
    const statusTabsContainer = document.getElementById('statusTabs');
    const payerFilter = document.getElementById('payerFilter');
    const searchFilter = document.getElementById('searchFilter');
    const batchDateFrom = document.getElementById('batchDateFrom');
    const batchDateTo = document.getElementById('batchDateTo');
    const invDateFrom = document.getElementById('invDateFrom');
    const invDateTo = document.getElementById('invDateTo');
    const recordCountDisplay = document.getElementById('recordCount');
    const totalAmountDisplay = document.getElementById('totalAmount');
    const btnExportExcel = document.getElementById('btnExportExcel');

    // Set default date range for Batch Date: 1st to last day of current month (UTC+8)
    const now = new Date(new Date().toLocaleString('en-US', { timeZone: 'Asia/Singapore' }));
    const firstDay = new Date(now.getFullYear(), now.getMonth(), 1);
    const lastDay = new Date(now.getFullYear(), now.getMonth() + 1, 0);
    const pad = (n) => String(n).padStart(2, '0');
    const toDateStr = (d) => `${d.getFullYear()}-${pad(d.getMonth()+1)}-${pad(d.getDate())}`;
    batchDateFrom.value = toDateStr(firstDay);
    batchDateTo.value = toDateStr(lastDay);

    // Populate payer filter dynamically from data
    const uniquePayers = [...new Set(invoicesData.map(i => i.payer_name).filter(Boolean))].sort();
    uniquePayers.forEach(p => {
        const opt = document.createElement('option');
        opt.value = p;
        opt.textContent = p;
        payerFilter.appendChild(opt);
    });

    // Modals
    const sendDocModalElement = document.getElementById('sendDocModal');
    const setReceivedModalElement = document.getElementById('setReceivedModal');
    const remarksModalElement = document.getElementById('remarksModal');
    const reviseModalElement = document.getElementById('reviseModal');
    const cancelInvModalElement = document.getElementById('cancelInvModal');
    const rowActionModalElement = document.getElementById('rowActionModal');

    let sendDocModal, setReceivedModal, remarksModal, reviseModal, cancelInvModal, rowActionModal;
    if (typeof bootstrap !== 'undefined') {
        sendDocModal = new bootstrap.Modal(sendDocModalElement);
        setReceivedModal = new bootstrap.Modal(setReceivedModalElement);
        remarksModal = new bootstrap.Modal(remarksModalElement);
        reviseModal = new bootstrap.Modal(reviseModalElement);
        cancelInvModal = new bootstrap.Modal(cancelInvModalElement);
        rowActionModal = new bootstrap.Modal(rowActionModalElement);
    }

    // ===========================
    //  STICKY POSITIONS
    // ===========================
    // Sticky positions are now hardcoded in CSS (left values)
    const updateStickyPositions = () => { /* no-op */ };

    const getActionAvailability = (item) => {
        return {
            canSent: (item.status === 'BATCHING' || item.status === 'REVISE') && !item.is_cancelled,
            canReceived: item.status === 'SENT' && !item.is_cancelled,
            canRevise: item.status === 'RECEIVED' && !item.is_cancelled,
            canPaid: item.status === 'RECEIVED' && !item.is_cancelled,
            isCancelledItem: item.is_cancelled
        };
    };

    const openNextModal = (nextModal) => {
        if (!nextModal) return;
        const isRowActionModalOpen = !!(rowActionModalElement && rowActionModalElement.classList.contains('show'));
        if (!rowActionModal || !isRowActionModalOpen) {
            nextModal.show();
            return;
        }

        const handleHidden = () => {
            rowActionModalElement.removeEventListener('hidden.bs.modal', handleHidden);
            nextModal.show();
        };

        rowActionModalElement.addEventListener('hidden.bs.modal', handleHidden);
        rowActionModal.hide();
    };

    const openActionFlow = (action, item) => {
        if (!item) return;

        if (action === 'SENT') {
            selectedIds.clear();
            selectedIds.add(item.id);
            document.getElementById('sdItemCount').textContent = '1';
            document.getElementById('inputRefNo').value = item.ref_no || '';
            openNextModal(sendDocModal);
        } else if (action === 'RECEIVED') {
            selectedIds.clear();
            selectedIds.add(item.id);
            document.getElementById('rcItemCount').textContent = '1';
            openNextModal(setReceivedModal);
        } else if (action === 'PAID') {
            rowActionModal?.hide();
            const today = new Date().toISOString().slice(0, 10);

            persistTrackingUpdate({
                invoice_no: item.invoice_no,
                status: 'PAID',
                ref_no: item.ref_no || null,
                courier_via: item.courier_via || null,
                tracking_no: item.tracking_no || null,
                sent_date: item.sent_date || null,
                received_date: item.received_date || null,
                paid_on: today,
                cancelled_date: item.cancelled_date || null,
                due_days: item.due_days ?? null,
                remarks: item.remarks || null
            }).catch(error => {
                alert(error.message || 'Failed to update paid status.');
            });
        } else if (action === 'REMARKS') {
            selectedIds.clear();
            selectedIds.add(item.id);
            document.getElementById('rmItemCount').textContent = '1';
            document.getElementById('inputRemarks').value = item.remarks || '';
            openNextModal(remarksModal);
        } else if (action === 'REVISE') {
            selectedIds.clear();
            selectedIds.add(item.id);
            document.getElementById('rvItemCount').textContent = '1';
            document.getElementById('inputReviseReason').value = '';
            openNextModal(reviseModal);
        } else if (action === 'CANCEL') {
            selectedIds.clear();
            selectedIds.add(item.id);
            document.getElementById('ccItemCount').textContent = '1';
            document.getElementById('inputCancelReason').value = '';
            openNextModal(cancelInvModal);
        }
    };

    const openRowActionModal = (item) => {
        if (!item) return;

        const { canSent, canReceived, canRevise, canPaid, isCancelledItem } = getActionAvailability(item);
        document.getElementById('rowActionMeta').textContent = `Invoice ${item.invoice_no || '-'} • Batch ${item.batch_number || '-'}`;
        document.getElementById('rowActionPatient').textContent = item.patient_name || '-';
        document.getElementById('rowActionPayer').textContent = item.payer_name || '-';
        document.getElementById('rowActionStatus').innerHTML = `<span class="badge-status ${item.status}" style="font-size: 0.68rem; padding: 0.24rem 0.5rem;">${item.status || '-'}</span>`;
        const autoZeroDate = getAutoZeroDate(item);
        document.getElementById('rowActionCancelInfo').innerHTML = item.is_cancelled
            ? `<div style="display:flex; flex-direction:column; gap:0.25rem; align-items:flex-end;">
                    <span class="cancel-flag">Cancelled ${formatDate(item.cancelled_date)}</span>
                    ${autoZeroDate ? `<span class="small" style="color: var(--muted-foreground); font-size: 0.72rem;">Auto-zero ${formatDate(autoZeroDate)}</span>` : ''}
               </div>`
            : '';

        const buttons = [];
        if (canSent) {
            buttons.push(`<button type="button" class="btn-shadcn action-modal-btn action-sent" data-row-action="SENT" data-id="${item.id}">Set Sent</button>`);
        }
        if (canReceived) {
            buttons.push(`<button type="button" class="btn-shadcn action-modal-btn action-received" data-row-action="RECEIVED" data-id="${item.id}">Set Received</button>`);
        }
        if (canRevise) {
            buttons.push(`<button type="button" class="btn-shadcn action-modal-btn action-revise" data-row-action="REVISE" data-id="${item.id}">Set Revise</button>`);
        }
        if (canPaid) {
            buttons.push(`<button type="button" class="btn-shadcn action-modal-btn action-paid" data-row-action="PAID" data-id="${item.id}">Set Paid</button>`);
        }

        buttons.push(
            `<button type="button" class="btn-shadcn btn-shadcn-outline action-modal-btn" data-row-action="REMARKS" data-id="${item.id}">Update Remarks</button>`
        );

        if (!isCancelledItem) {
            buttons.push(`<button type="button" class="btn-shadcn action-modal-btn action-danger" data-row-action="CANCEL" data-id="${item.id}">Cancel Invoice</button>`);
        }

        document.getElementById('rowActionButtons').innerHTML = buttons.join('');
        rowActionModal?.show();
    };

    // ===========================
    //  RENDER TABS
    // ===========================
    const renderTabs = () => {
        statusTabsContainer.innerHTML = '';

        // Get data filtered by date/payer/search (but NOT by tab)
        const getBaseFilteredData = () => {
            const payerVal = payerFilter.value;
            const searchVal = searchFilter.value.toLowerCase();
            const bFromVal = batchDateFrom.value;
            const bToVal = batchDateTo.value;
            const iFromVal = invDateFrom.value;
            const iToVal = invDateTo.value;

            return invoicesData.filter(item => {
                if (payerVal !== 'All' && item.payer_name !== payerVal) return false;
                if (searchVal !== '') {
                    const haystack = `${item.patient_name} ${item.mrn} ${item.invoice_no} ${item.batch_number || ''}`.toLowerCase();
                    if (!haystack.includes(searchVal)) return false;
                }
                const batchVal = item.batch_date ? item.batch_date.slice(0, 10) : null;
                if (bFromVal && (!batchVal || batchVal < bFromVal)) return false;
                if (bToVal && (!batchVal || batchVal > bToVal)) return false;
                const invVal = item.invoice_printed ? item.invoice_printed.slice(0, 10) : null;
                if (iFromVal && (!invVal || invVal < iFromVal)) return false;
                if (iToVal && (!invVal || invVal > iToVal)) return false;
                return true;
            });
        };

        const baseData = getBaseFilteredData();

        tabsDef.forEach(tab => {
            const count = baseData.filter(tab.query).length;
            const btn = document.createElement('button');
            btn.className = `status-tab ${activeTab === tab.key ? 'active' : ''}`;
            btn.innerHTML = `${tab.label} <span class="tab-count">${count}</span>`;
            btn.addEventListener('click', () => {
                activeTab = tab.key;
                currentPage = 1;
                renderTabs();
                renderTable();
            });
            statusTabsContainer.appendChild(btn);
        });
    };

    // ===========================
    //  FILTER DATA
    // ===========================
    const getFilteredData = () => {
        const payerVal = payerFilter.value;
        const searchVal = searchFilter.value.toLowerCase();
        const bFromVal = batchDateFrom.value;
        const bToVal = batchDateTo.value;
        const iFromVal = invDateFrom.value;
        const iToVal = invDateTo.value;

        // Get the active tab's query
        const tabQuery = tabsDef.find(t => t.key === activeTab)?.query || (() => true);

        return invoicesData.filter(item => {
            // Tab filter
            if (!tabQuery(item)) return false;

            // Payer filter
            if (payerVal !== 'All' && item.payer_name !== payerVal) return false;

            // Search filter
            if (searchVal !== '') {
                const haystack = `${item.patient_name} ${item.mrn} ${item.invoice_no} ${item.batch_number || ''}`.toLowerCase();
                if (!haystack.includes(searchVal)) return false;
            }

            // Batch Date range filter
            const batchVal = item.batch_date ? item.batch_date.slice(0, 10) : null;
            if (bFromVal && (!batchVal || batchVal < bFromVal)) return false;
            if (bToVal && (!batchVal || batchVal > bToVal)) return false;

            // Invoice Printed Date range filter
            const invVal = item.invoice_printed ? item.invoice_printed.slice(0, 10) : null;
            if (iFromVal && (!invVal || invVal < iFromVal)) return false;
            if (iToVal && (!invVal || invVal > iToVal)) return false;

            return true;
        });
    };

    // ===========================
    //  RENDER TABLE
    // ===========================
    const renderTable = () => {
        const data = getFilteredData();
        tableBody.innerHTML = '';

        // Pagination calculation
        const totalRecords = data.length;
        const totalPages = pageSize === 'all' ? 1 : Math.ceil(totalRecords / pageSize);
        if (currentPage > totalPages) currentPage = totalPages || 1;

        const startIdx = pageSize === 'all' ? 0 : (currentPage - 1) * pageSize;
        const endIdx = pageSize === 'all' ? totalRecords : Math.min(startIdx + pageSize, totalRecords);
        const pageData = data.slice(startIdx, endIdx);

        // Grand totals (across ALL filtered data, not just current page)
        let totalVal = 0;
        let totalBefore = 0;
        let totalAfter = 0;
        let totalPaid = 0;
        data.forEach(item => {
            const displayBalance = (item.is_cancelled && shouldZeroBalance(item)) ? 0 : item.balance;
            totalVal += displayBalance;
            totalBefore += (item.before_discount || 0);
            totalAfter += (item.after_discount || 0);
            totalPaid += (item.paid_amount || 0);
        });

        if (data.length === 0) {
            tableBody.innerHTML = `<tr>
                <td class="sticky-col sticky-batch"></td>
                <td class="sticky-col sticky-invno"></td>
                <td class="sticky-col sticky-adm"></td>
                <td class="sticky-col sticky-pat"></td>
                <td class="sticky-col sticky-ref"></td>
                <td class="sticky-col sticky-status"></td>
                <td colspan="12" class="text-center py-5 text-muted">No records found matching the criteria.</td>
            </tr>`;
            recordCountDisplay.textContent = 'Showing 0 records';
            totalAmountDisplay.textContent = 'Total Balance: IDR 0';
            document.getElementById('pageInfo').textContent = 'Page 0 of 0';
            document.getElementById('btnPrevPage').disabled = true;
            document.getElementById('btnNextPage').disabled = true;

            document.getElementById('tfCount').textContent = '0';
            document.getElementById('tfBefore').textContent = formatCurrency(0);
            document.getElementById('tfAfter').textContent = formatCurrency(0);
            document.getElementById('tfPaid').textContent = formatCurrency(0);
            document.getElementById('tfBalance').textContent = formatCurrency(0);
            return;
        }

        // Render only current page data
        pageData.forEach(item => {
            const displayBalance = (item.is_cancelled && shouldZeroBalance(item)) ? 0 : item.balance;

            const tr = document.createElement('tr');
            tr.dataset.id = item.id;
            if (item.is_cancelled) tr.classList.add('row-cancelled');

            // Cancel flag HTML
            const cancelSourceLabel = item.cancel_source === 'BILLING'
                ? 'Billing'
                : item.cancel_source === 'TRACKING'
                    ? 'Track'
                    : item.cancel_source === 'BOTH'
                        ? 'Both'
                        : '';
            const cancelFlagHtml = item.is_cancelled
                ? `<span class="cancel-flag" title="Cancelled on ${formatDate(item.cancelled_date)}">CANCEL</span>${cancelSourceLabel ? `<span class="cancel-source-badge">${cancelSourceLabel}</span>` : ''}`
                : '';

            const { canSent, canReceived, canRevise, canPaid, isCancelledItem } = getActionAvailability(item);

            // SVG Icons
            const iconSent = `<svg class="action-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 2L11 13"/><path d="M22 2L15 22L11 13L2 9L22 2Z"/></svg>`;
            const iconReceived = `<svg class="action-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>`;
            const iconRevise = `<svg class="action-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 3v6h-6"/><path d="M3 21v-6h6"/><path d="M21 9a9 9 0 0 0-15-6.7L3 5"/><path d="M3 15a9 9 0 0 0 15 6.7l3-2.7"/></svg>`;
            const iconPaid = `<svg class="action-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>`;
            const iconRemarks = `<svg class="action-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>`;
            const iconCancel = `<svg class="action-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>`;

            // Action dropdown
            let actionButtons = '';
            if (!isCancelledItem) {
                actionButtons = `
                <div class="dropdown d-inline-block">
                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="font-size: 0.75rem; padding: 2px 8px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 3px;"><circle cx="12" cy="12" r="1"/><circle cx="12" cy="5" r="1"/><circle cx="12" cy="19" r="1"/></svg>
                        Actions
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" style="min-width: 200px;">
                        <li>
                            <a class="dropdown-item action-dd-item ${canSent ? '' : 'disabled'}" href="#" data-id="${item.id}" data-action="SENT" ${canSent ? '' : 'aria-disabled="true" tabindex="-1"'} style="${canSent ? 'color: #1d4ed8;' : ''}">
                                ${iconSent}
                                <span>Set Sent</span>
                                ${canSent ? '<span class="action-badge badge-next">NEXT</span>' : ''}
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item action-dd-item action-primary ${canPaid ? '' : 'disabled'}" href="#" data-id="${item.id}" data-action="PAID" ${canPaid ? '' : 'aria-disabled="true" tabindex="-1"'}>
                                ${iconPaid}
                                <span>Set Paid</span>
                                ${canPaid ? '<span class="action-badge badge-next">NEXT</span>' : ''}
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item action-dd-item ${canReceived ? '' : 'disabled'}" href="#" data-id="${item.id}" data-action="RECEIVED" ${canReceived ? '' : 'aria-disabled="true" tabindex="-1"'} style="${canReceived ? 'color: #334155;' : ''}">
                                ${iconReceived}
                                <span>Set Received</span>
                                ${canReceived ? '<span class="action-badge badge-next">NEXT</span>' : ''}
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item action-dd-item ${canRevise ? '' : 'disabled'}" href="#" data-id="${item.id}" data-action="REVISE" ${canRevise ? '' : 'aria-disabled="true" tabindex="-1"'} style="${canRevise ? 'color: #dc2626;' : ''}">
                                ${iconRevise}
                                <span>Set Revise</span>
                                ${canRevise ? '<span class="action-badge badge-next">NEXT</span>' : ''}
                            </a>
                        </li>
                        <li><hr class="dropdown-divider" style="margin: 0.25rem 0;"></li>
                        <li>
                            <a class="dropdown-item action-dd-item" href="#" data-id="${item.id}" data-action="REMARKS">
                                ${iconRemarks}
                                <span>Remarks</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item action-dd-item text-danger" href="#" data-id="${item.id}" data-action="CANCEL">
                                ${iconCancel}
                                <span>Cancel</span>
                            </a>
                        </li>
                    </ul>
                </div>
                `;
            }

            tr.innerHTML = `
                <td class="sticky-col sticky-batch">
                    <div class="font-weight-bold" style="color: var(--foreground);">${item.batch_number || '-'}</div>
                    <div style="font-size: 0.75rem; color: var(--muted-foreground);">${formatDate(item.batch_date)}</div>
                </td>
                <td class="sticky-col sticky-invno">
                    <div class="font-weight-bold" style="color: var(--foreground);">${item.invoice_no || '-'}</div>
                    <div style="font-size: 0.75rem; color: var(--muted-foreground);">Printed: ${formatDate(item.invoice_printed)}</div>
                    ${item.cancelled_date ? `<div style="font-size: 0.7rem; color: #dc2626; font-weight: 600;">Cancelled: ${formatDate(item.cancelled_date)}</div>` : ''}
                </td>
                <td class="sticky-col sticky-adm">
                    <div class="font-weight-bold" style="color: var(--foreground);">MRN: ${item.mrn || '-'}</div>
                    <div style="font-size: 0.75rem; color: var(--muted-foreground);">Ep: ${item.episode_no || '-'}</div>
                    <div style="font-size: 0.75rem; color: var(--muted-foreground);">${formatDate(item.adm_date)} &bull; ${item.adm_type || '-'}</div>
                </td>
                <td class="sticky-col sticky-pat">
                    <div class="font-weight-bold" style="color: var(--foreground);">${item.patient_name || '-'}</div>
                    <div style="font-size: 0.65rem; color: var(--muted-foreground); display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">${item.payer_name || '-'}</div>
                    <div style="font-size: 0.7rem; color: var(--muted-foreground); opacity: 0.7;">${item.nationality || '-'}</div>
                </td>
                <td class="sticky-col sticky-ref">${item.ref_no || '-'}</td>
                <td class="sticky-col sticky-status">
                    <div style="display: flex; flex-direction: column; align-items: center; gap: 2px;">
                        <span class="badge-status ${item.status}" style="font-size: 0.6rem; padding: 0.2rem 0.4rem;">${item.status}</span>
                        ${cancelFlagHtml}
                    </div>
                </td>
                <td class="text-end">${formatCurrency(item.before_discount || 0)}</td>
                <td class="text-end">${formatCurrency(item.after_discount || 0)}</td>
                <td class="text-end">${formatCurrency(item.paid_amount || 0)}</td>
                <td class="text-end" style="color: ${displayBalance > 0 ? '#059669' : 'var(--foreground)'};">${formatCurrency(displayBalance)}</td>
                <td>${formatDate(item.sent_date)}</td>
                <td>${item.courier_via || '-'}</td>
                <td style="font-size: 0.8rem;">${item.tracking_no || '-'}</td>
                <td>${formatDate(item.received_date)}</td>
                <td>${formatDate(item.paid_on)}</td>
                <td style="border-right: 2px solid var(--border) !important;" class="text-center">${item.due_days ?? '-'}</td>
                <td>
                    <div style="max-width: 200px; white-space: normal; font-size: 0.8rem; color: var(--muted-foreground);">${item.remarks || '-'}</div>
                </td>
                <td>
                    <div class="d-flex flex-wrap gap-1">
                        ${actionButtons}
                    </div>
                </td>
            `;

            tableBody.appendChild(tr);
        });

        // Update Summary Row (grand totals across all filtered data)
        document.getElementById('tfCount').textContent = totalRecords;
        document.getElementById('tfBefore').textContent = formatCurrency(totalBefore);
        document.getElementById('tfAfter').textContent = formatCurrency(totalAfter);
        document.getElementById('tfPaid').textContent = formatCurrency(totalPaid);
        document.getElementById('tfBalance').textContent = formatCurrency(totalVal);

        recordCountDisplay.textContent = `Showing ${startIdx + 1}-${endIdx} of ${totalRecords} records`;
        totalAmountDisplay.textContent = `Total Balance: ${formatCurrency(totalVal)}`;

        // Pagination controls
        document.getElementById('pageInfo').textContent = `Page ${currentPage} of ${totalPages}`;
        document.getElementById('btnPrevPage').disabled = currentPage <= 1;
        document.getElementById('btnNextPage').disabled = currentPage >= totalPages;

        updateStickyPositions();
    };

    // ===========================
    //  UPDATE UI (tabs + table)
    // ===========================
    const updateUI = () => {
        renderTabs();
        renderTable();
    };

    // ===========================
    //  PAGINATION EVENT LISTENERS
    // ===========================
    document.getElementById('pageSizeSelect').addEventListener('change', (e) => {
        const val = e.target.value;
        pageSize = val === 'all' ? 'all' : parseInt(val);
        currentPage = 1;
        renderTable();
    });

    document.getElementById('btnPrevPage').addEventListener('click', () => {
        if (currentPage > 1) {
            currentPage--;
            renderTable();
        }
    });

    document.getElementById('btnNextPage').addEventListener('click', () => {
        const data = getFilteredData();
        const totalPages = pageSize === 'all' ? 1 : Math.ceil(data.length / pageSize);
        if (currentPage < totalPages) {
            currentPage++;
            renderTable();
        }
    });

    // ===========================
    //  EVENT LISTENERS
    // ===========================
    document.getElementById('rowActionButtons').addEventListener('click', (e) => {
        const rowActionTrigger = e.target.closest('[data-row-action]');
        if (!rowActionTrigger) return;

        e.preventDefault();
        const id = parseInt(rowActionTrigger.getAttribute('data-id'));
        const action = rowActionTrigger.getAttribute('data-row-action');
        const item = invoicesData.find(i => i.id === id);
        openActionFlow(action, item);
    });

    tableBody.addEventListener('click', (e) => {
        const itemEl = e.target.closest('.action-dd-item');
        if (itemEl) {
            e.preventDefault();

            const id = parseInt(itemEl.getAttribute('data-id'));
            const action = itemEl.getAttribute('data-action');
            const item = invoicesData.find(i => i.id === id);
            if (!item) return;

            if (itemEl.classList.contains('disabled') || itemEl.getAttribute('aria-disabled') === 'true') {
                return;
            }

            openActionFlow(action, item);
            return;
        }

        const rowEl = e.target.closest('tr[data-id]');
        const clickedStickyArea = !!e.target.closest('.sticky-col');
        if (rowEl && !clickedStickyArea && !e.target.closest('.dropdown') && !e.target.closest('button') && !e.target.closest('a') && !e.target.closest('input') && !e.target.closest('select') && !e.target.closest('textarea')) {
            const id = parseInt(rowEl.dataset.id);
            const item = invoicesData.find(i => i.id === id);
            openRowActionModal(item);
            return;
        }
    });

    payerFilter.addEventListener('change', () => { currentPage = 1; updateUI(); });
    searchFilter.addEventListener('input', () => { currentPage = 1; updateUI(); });
    batchDateFrom.addEventListener('change', () => { currentPage = 1; updateUI(); });
    batchDateTo.addEventListener('change', () => { currentPage = 1; updateUI(); });
    invDateFrom.addEventListener('change', () => { currentPage = 1; updateUI(); });
    invDateTo.addEventListener('change', () => { currentPage = 1; updateUI(); });

    document.getElementById('resetBtn').addEventListener('click', () => {
        payerFilter.value = 'All';
        searchFilter.value = '';
        batchDateFrom.value = toDateStr(firstDay);
        batchDateTo.value = toDateStr(lastDay);
        invDateFrom.value = '';
        invDateTo.value = '';
        activeTab = 'ALL';
        currentPage = 1;
        updateUI();
    });

    // ===========================
    //  MODAL ACTIONS
    // ===========================
    document.getElementById('btnSubmitSendDoc').addEventListener('click', async () => {
        const refNo = document.getElementById('inputRefNo').value;
        const via = document.getElementById('inputVia').value;
        const tracking = document.getElementById('inputTrackingNo').value;
        const sDate = document.getElementById('inputSentDate').value;
        if (!tracking) return alert('Tracking Number is required');

        const selectedItem = invoicesData.find(inv => selectedIds.has(inv.id));
        if (!selectedItem) return alert('Invoice not found.');

        try {
            await persistTrackingUpdate({
                invoice_no: selectedItem.invoice_no,
                status: 'SENT',
                ref_no: refNo || selectedItem.ref_no || null,
                courier_via: via,
                tracking_no: tracking,
                sent_date: sDate || null,
                received_date: selectedItem.received_date || null,
                paid_on: selectedItem.paid_on || null,
                cancelled_date: selectedItem.cancelled_date || null,
                due_days: selectedItem.due_days ?? null,
                remarks: selectedItem.remarks || null
            });

            sendDocModal?.hide();
            document.getElementById('inputTrackingNo').value = '';
            document.getElementById('inputRefNo').value = '';
            selectedIds.clear();
        } catch (error) {
            alert(error.message || 'Failed to update tracking.');
        }
    });

    document.getElementById('btnSubmitReceived').addEventListener('click', async () => {
        const rDate = document.getElementById('inputReceivedDate').value;
        const selectedItem = invoicesData.find(inv => selectedIds.has(inv.id));
        if (!selectedItem) return alert('Invoice not found.');

        try {
            await persistTrackingUpdate({
                invoice_no: selectedItem.invoice_no,
                status: 'RECEIVED',
                ref_no: selectedItem.ref_no || null,
                courier_via: selectedItem.courier_via || null,
                tracking_no: selectedItem.tracking_no || null,
                sent_date: selectedItem.sent_date || null,
                received_date: rDate || null,
                paid_on: selectedItem.paid_on || null,
                cancelled_date: selectedItem.cancelled_date || null,
                due_days: selectedItem.due_days ?? null,
                remarks: selectedItem.remarks || null
            });

            setReceivedModal?.hide();
            selectedIds.clear();
        } catch (error) {
            alert(error.message || 'Failed to update tracking.');
        }
    });

    document.getElementById('btnSubmitRemarks').addEventListener('click', async () => {
        const rmVal = document.getElementById('inputRemarks').value;
        const selectedItem = invoicesData.find(inv => selectedIds.has(inv.id));
        if (!selectedItem) return alert('Invoice not found.');

        try {
            await persistTrackingUpdate({
                invoice_no: selectedItem.invoice_no,
                status: selectedItem.status || 'BATCHING',
                ref_no: selectedItem.ref_no || null,
                courier_via: selectedItem.courier_via || null,
                tracking_no: selectedItem.tracking_no || null,
                sent_date: selectedItem.sent_date || null,
                received_date: selectedItem.received_date || null,
                paid_on: selectedItem.paid_on || null,
                cancelled_date: selectedItem.cancelled_date || null,
                due_days: selectedItem.due_days ?? null,
                remarks: rmVal || null
            });

            remarksModal?.hide();
            document.getElementById('inputRemarks').value = '';
            selectedIds.clear();
        } catch (error) {
            alert(error.message || 'Failed to save remarks.');
        }
    });

    document.getElementById('btnSubmitRevise').addEventListener('click', async () => {
        const reviseReason = document.getElementById('inputReviseReason').value;
        const selectedItem = invoicesData.find(inv => selectedIds.has(inv.id));
        if (!selectedItem) return alert('Invoice not found.');

        try {
            await persistTrackingUpdate({
                invoice_no: selectedItem.invoice_no,
                status: 'REVISE',
                ref_no: selectedItem.ref_no || null,
                courier_via: selectedItem.courier_via || null,
                tracking_no: selectedItem.tracking_no || null,
                sent_date: selectedItem.sent_date || null,
                received_date: selectedItem.received_date || null,
                paid_on: selectedItem.paid_on || null,
                cancelled_date: selectedItem.cancelled_date || null,
                due_days: selectedItem.due_days ?? null,
                remarks: reviseReason ? `[REVISE] ${reviseReason}` : (selectedItem.remarks ? `[REVISE] ${selectedItem.remarks}` : '[REVISE]')
            });

            reviseModal?.hide();
            document.getElementById('inputReviseReason').value = '';
            selectedIds.clear();
        } catch (error) {
            alert(error.message || 'Failed to save revise status.');
        }
    });

    document.getElementById('btnSubmitCancel').addEventListener('click', async () => {
        const reason = document.getElementById('inputCancelReason').value;
        const selectedItem = invoicesData.find(inv => selectedIds.has(inv.id));
        if (!selectedItem) return alert('Invoice not found.');
        const today = new Date().toISOString().slice(0, 10);

        try {
            await persistTrackingUpdate({
                invoice_no: selectedItem.invoice_no,
                status: selectedItem.status || 'BATCHING',
                ref_no: selectedItem.ref_no || null,
                courier_via: selectedItem.courier_via || null,
                tracking_no: selectedItem.tracking_no || null,
                sent_date: selectedItem.sent_date || null,
                received_date: selectedItem.received_date || null,
                paid_on: selectedItem.paid_on || null,
                cancelled_date: today,
                due_days: selectedItem.due_days ?? null,
                remarks: reason ? `[CANCELLED] ${reason}` : (selectedItem.remarks ? `[CANCELLED] ${selectedItem.remarks}` : '[CANCELLED]')
            });

            cancelInvModal?.hide();
            document.getElementById('inputCancelReason').value = '';
            selectedIds.clear();
        } catch (error) {
            alert(error.message || 'Failed to save cancellation note.');
        }
    });

    // ===========================
    //  EXPORT EXCEL
    // ===========================
    const exportExcel = () => {
        if (typeof XLSX === 'undefined') {
            alert('Excel library is still loading, please wait a moment.');
            return;
        }

        const wb = XLSX.utils.book_new();

        tabsDef.forEach(tab => {
            const tabData = invoicesData.filter(tab.query);

            const dbHeaders = [
                'id', 'payer_name', 'patient_name', 'mrn', 'invoice_no', 'invoice_date',
                'amount', 'paid_amount', 'balance', 'due_days', 'status',
                'ref_no', 'courier_via', 'tracking_no', 'sent_date', 'received_date',
                'paid_on', 'remarks', 'is_cancelled', 'cancelled_date'
            ];

            let aoa = [dbHeaders];

            tabData.forEach(item => {
                const displayBalance = (item.is_cancelled && shouldZeroBalance(item)) ? 0 : item.balance;
                aoa.push([
                    item.id,
                    item.payer_name || '',
                    item.patient_name || '',
                    item.mrn || '',
                    item.invoice_no || '',
                    item.invoice_date || '',
                    item.amount || 0,
                    item.paid_amount || 0,
                    displayBalance,
                    item.due_days ?? '',
                    item.status || '',
                    item.ref_no || '',
                    item.courier_via || '',
                    item.tracking_no || '',
                    item.sent_date || '',
                    item.received_date || '',
                    item.paid_on || '',
                    item.remarks || '',
                    item.is_cancelled ? 'YES' : 'NO',
                    item.cancelled_date || ''
                ]);
            });

            const ws = XLSX.utils.aoa_to_sheet(aoa);
            const safeSheetName = tab.label.replace(/[\[\]\*\?\/\\:]/g, "").substring(0, 31);
            XLSX.utils.book_append_sheet(wb, ws, safeSheetName);
        });

        const fileName = `AR_Tracking_Export_${new Date().toISOString().slice(0,10)}.xlsx`;
        XLSX.writeFile(wb, fileName);
    };

    if (btnExportExcel) {
        btnExportExcel.addEventListener('click', exportExcel);
    }

    // ===========================
    //  INITIAL RENDER
    // ===========================
    updateUI();
</script>
@endpush
