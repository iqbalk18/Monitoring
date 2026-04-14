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

        .row-cancelled {
            background-color: rgba(220, 38, 38, 0.03);
        }

        .dark .row-cancelled {
            background-color: rgba(220, 38, 38, 0.06);
        }

        .row-cancelled td {
            color: var(--muted-foreground) !important;
        }

        /* Don't dim status/action columns */
        .row-cancelled td:nth-last-child(-n+2) {
            color: inherit !important;
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

        .hidden-col { display: none; }

        /* ===== Sticky Columns ===== */
        .sticky-col {
            position: sticky !important;
            background-color: var(--muted);
            z-index: 2;
        }

        tbody .sticky-col {
            background-color: var(--card);
        }

        tbody tr:hover .sticky-col {
            background-color: var(--muted);
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

        .sticky-date { left: 0; width: 90px; min-width: 90px; max-width: 90px; white-space: normal; }
        .sticky-invno { left: 90px; width: 90px; min-width: 90px; max-width: 90px; white-space: normal; }
        .sticky-mrn { left: 180px; width: 90px; min-width: 90px; max-width: 90px; white-space: normal; }
        .sticky-patient { left: 270px; width: 180px; min-width: 180px; max-width: 180px; white-space: normal; }
        .sticky-ref { left: 450px; width: 110px; min-width: 110px; max-width: 110px; white-space: normal; border-right: 2px solid var(--border); }

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
    </style>
@endpush

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0" style="color: var(--foreground); font-weight: 700;">AR Tracking</h1>
            <p class="text-muted">Monitor accounts receivable document statuses.</p>
        </div>
    </div>

    <!-- Filter Header -->
    <div class="filter-header">
        <div class="row g-3">
            <div class="col-md-2">
                <label for="dateFrom" class="form-label" style="font-size: 0.875rem; font-weight: 500; color: var(--foreground);">Date From</label>
                <input type="date" id="dateFrom" class="form-control" style="background-color: var(--card); border-color: var(--input); color: var(--foreground);">
            </div>
            <div class="col-md-2">
                <label for="dateTo" class="form-label" style="font-size: 0.875rem; font-weight: 500; color: var(--foreground);">Date To</label>
                <input type="date" id="dateTo" class="form-control" style="background-color: var(--card); border-color: var(--input); color: var(--foreground);">
            </div>
            <div class="col-md-3">
                <label for="payerFilter" class="form-label" style="font-size: 0.875rem; font-weight: 500; color: var(--foreground);">Payer</label>
                <select id="payerFilter" class="form-select" style="background-color: var(--card); border-color: var(--input); color: var(--foreground);">
                    <option value="All">All Payers</option>
                    <option value="Global Excel">Global Excel</option>
                    <option value="Admedika">Admedika</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="searchFilter" class="form-label" style="font-size: 0.875rem; font-weight: 500; color: var(--foreground);">Search</label>
                <div class="input-group">
                    <span class="input-group-text" style="background-color: var(--muted); border-color: var(--input); color: var(--muted-foreground);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    </span>
                    <input type="text" id="searchFilter" class="form-control" placeholder="Patient, MRN, Inv No..." style="background-color: var(--card); border-color: var(--input); color: var(--foreground);">
                </div>
            </div>
            <div class="col-md-2 d-flex align-items-end justify-content-end gap-2">
                 <button class="btn-shadcn btn-shadcn-outline w-100" id="resetBtn">
                     Reset
                 </button>
                 <button class="btn-shadcn btn-shadcn-primary w-100" id="btnExportExcel">
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
                        <th class="sticky-col sticky-date" rowspan="2" style="z-index: 5;">Inv Dat</th>
                        <th class="sticky-col sticky-invno" rowspan="2" style="z-index: 5;">Inv No</th>
                        <th class="sticky-col sticky-mrn" rowspan="2" style="z-index: 5;">MRN</th>
                        <th class="sticky-col sticky-patient" rowspan="2" style="z-index: 5;">Patient Name</th>
                        <th class="sticky-col sticky-ref" rowspan="2" style="border-right: 2px solid var(--border) !important; z-index: 5;">Ref No</th>
                        <th colspan="11" class="text-center" style="border-bottom: 1px solid var(--border); border-right: 2px solid var(--border) !important;">💰 Section: Tracking</th>
                        <th rowspan="2">Remarks</th>
                        <th rowspan="2">Status</th>
                        <th rowspan="2">Action</th>
                    </tr>
                    <tr>
                        <th>Amount IDR</th>
                        <th>Curr</th>
                        <th>Cur</th>
                        <th>Cur (2)</th>
                        <th>Paid</th>
                        <th>Balance</th>
                        <th>Sent Doc</th>
                        <th>VIA</th>
                        <th>Tracking#</th>
                        <th>Rcvd date</th>
                        <th style="border-right: 2px solid var(--border) !important;">Due</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <!-- JS will populate rows -->
                </tbody>
                <tfoot id="tableFooter">
                    <tr>
                        <td class="sticky-col sticky-date text-center" id="tfCount">0</td>
                        <td class="sticky-col sticky-invno"></td>
                        <td class="sticky-col sticky-mrn"></td>
                        <td class="sticky-col sticky-patient text-end" style="padding-right: 1.5rem;">Grand Total</td>
                        <td class="sticky-col sticky-ref" style="border-right: 2px solid var(--border) !important;"></td>
                        <td class="text-end" id="tfAmount">0</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="text-end" id="tfPaid">0</td>
                        <td class="text-end" style="color: #059669;" id="tfBalance">0</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td style="border-right: 2px solid var(--border) !important;"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="d-flex justify-content-between align-items-center p-3 border-top">
            <div class="text-sm" style="color: var(--muted-foreground);" id="recordCount">
                Showing 0 records
            </div>
            <div class="text-sm font-weight-bold" style="color: var(--foreground);" id="totalAmount">
                Total Balance: 0
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
                        <label class="form-label font-weight-bold small">Ref No / Cover Note No</label>
                        <input type="text" class="form-control" id="inputRefNo" placeholder="e.g. 25-JKT001">
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-weight-bold small">Courier VIA</label>
                        <select class="form-select" id="inputVia" required>
                            <option value="JNE">JNE</option>
                            <option value="TIKI">TIKI</option>
                            <option value="Pos Indonesia">Pos Indonesia</option>
                            <option value="Internal Courier">Internal Courier</option>
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

@endsection

@push('scripts')
<!-- SheetJS Library for Excel Export -->
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
<script>
    // ===========================
    //  DATA
    // ===========================
    let invoicesData = @json($dummyInvoices);
    let activeTab = 'ALL';
    let selectedIds = new Set();

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
        return date.toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: '2-digit' }).replace(/ /g, '-');
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

    // ===========================
    //  DOM REFS
    // ===========================
    const tableBody = document.getElementById('tableBody');
    const statusTabsContainer = document.getElementById('statusTabs');
    const payerFilter = document.getElementById('payerFilter');
    const searchFilter = document.getElementById('searchFilter');
    const dateFrom = document.getElementById('dateFrom');
    const dateTo = document.getElementById('dateTo');
    const recordCountDisplay = document.getElementById('recordCount');
    const totalAmountDisplay = document.getElementById('totalAmount');
    const btnExportExcel = document.getElementById('btnExportExcel');

    // Modals
    const sendDocModalElement = document.getElementById('sendDocModal');
    const setReceivedModalElement = document.getElementById('setReceivedModal');
    const remarksModalElement = document.getElementById('remarksModal');
    const cancelInvModalElement = document.getElementById('cancelInvModal');

    let sendDocModal, setReceivedModal, remarksModal, cancelInvModal;
    if (typeof bootstrap !== 'undefined') {
        sendDocModal = new bootstrap.Modal(sendDocModalElement);
        setReceivedModal = new bootstrap.Modal(setReceivedModalElement);
        remarksModal = new bootstrap.Modal(remarksModalElement);
        cancelInvModal = new bootstrap.Modal(cancelInvModalElement);
    }

    // ===========================
    //  STICKY POSITIONS
    // ===========================
    const updateStickyPositions = () => {
        setTimeout(() => {
            const getWidth = (className) => {
                const head = document.querySelector(`thead th.${className}`);
                return head ? head.offsetWidth : 0;
            };

            let currentLeft = 0;
            const cols = [
                { class: 'sticky-date' },
                { class: 'sticky-invno' },
                { class: 'sticky-mrn' },
                { class: 'sticky-patient' },
                { class: 'sticky-ref' }
            ];

            cols.forEach((col) => {
                document.querySelectorAll(`.${col.class}`).forEach(el => {
                    el.style.left = `${currentLeft}px`;
                });
                currentLeft += getWidth(col.class);
            });
        }, 50);
    };

    // ===========================
    //  RENDER TABS
    // ===========================
    const renderTabs = () => {
        statusTabsContainer.innerHTML = '';

        tabsDef.forEach(tab => {
            const count = invoicesData.filter(tab.query).length;
            const btn = document.createElement('button');
            btn.className = `status-tab ${activeTab === tab.key ? 'active' : ''}`;
            btn.innerHTML = `${tab.label} <span class="tab-count">${count}</span>`;
            btn.addEventListener('click', () => {
                activeTab = tab.key;
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
        const fromVal = dateFrom.value;
        const toVal = dateTo.value;

        // Get the active tab's query
        const tabQuery = tabsDef.find(t => t.key === activeTab)?.query || (() => true);

        return invoicesData.filter(item => {
            // Tab filter
            if (!tabQuery(item)) return false;

            // Payer filter
            if (payerVal !== 'All' && item.payer_name !== payerVal) return false;

            // Search filter
            if (searchVal !== '') {
                const haystack = `${item.patient_name} ${item.mrn} ${item.invoice_no}`.toLowerCase();
                if (!haystack.includes(searchVal)) return false;
            }

            // Date range filter (based on invoice_date)
            if (fromVal && item.invoice_date < fromVal) return false;
            if (toVal && item.invoice_date > toVal) return false;

            return true;
        });
    };

    // ===========================
    //  RENDER TABLE
    // ===========================
    const renderTable = () => {
        const data = getFilteredData();
        tableBody.innerHTML = '';

        let totalVal = 0;
        let totalAmount = 0;
        let totalPaid = 0;

        if (data.length === 0) {
            tableBody.innerHTML = `<tr><td colspan="19" class="text-center py-5 text-muted">No records found matching the criteria.</td></tr>`;
            recordCountDisplay.textContent = 'Showing 0 records';
            totalAmountDisplay.textContent = 'Total Balance: IDR 0';

            document.getElementById('tfCount').textContent = '0';
            document.getElementById('tfAmount').textContent = formatCurrency(0);
            document.getElementById('tfPaid').textContent = formatCurrency(0);
            document.getElementById('tfBalance').textContent = formatCurrency(0);
            return;
        }

        data.forEach(item => {
            // Apply client-side zero balance for cancelled invoices
            const displayBalance = (item.is_cancelled && shouldZeroBalance(item)) ? 0 : item.balance;

            totalVal += displayBalance;
            totalAmount += item.amount;
            totalPaid += (item.paid_amount || 0);

            const tr = document.createElement('tr');
            if (item.is_cancelled) tr.classList.add('row-cancelled');

            // Cancel flag HTML
            const cancelFlagHtml = item.is_cancelled
                ? `<span class="cancel-flag" title="Cancelled on ${formatDate(item.cancelled_date)}">🚫 CANCEL</span>`
                : '';

            // Action dropdown
            let actionButtons = `
                <div class="dropdown">
                  <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="font-size: 0.75rem;">
                    Action
                  </button>
                  <ul class="dropdown-menu shadow-sm" style="font-size: 0.875rem;">
                    <li><a class="dropdown-item action-dd-item" href="javascript:void(0)" data-id="${item.id}" data-action="BATCHING">Set Batching</a></li>
                    <li><a class="dropdown-item action-dd-item" href="javascript:void(0)" data-id="${item.id}" data-action="SENT">Set Sent</a></li>
                    <li><a class="dropdown-item action-dd-item" href="javascript:void(0)" data-id="${item.id}" data-action="RECEIVED">Set Received</a></li>
                    <li><a class="dropdown-item action-dd-item" href="javascript:void(0)" data-id="${item.id}" data-action="REVISE">Set Revise</a></li>
                    <li><a class="dropdown-item action-dd-item" href="javascript:void(0)" data-id="${item.id}" data-action="PAID">Set Paid</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item action-dd-item" href="javascript:void(0)" data-id="${item.id}" data-action="REMARKS">Update Remarks</a></li>
                    ${!item.is_cancelled ? `<li><a class="dropdown-item action-dd-item" href="javascript:void(0)" data-id="${item.id}" data-action="CANCEL" style="color: #dc2626; font-weight: 600;">⛔ Cancel Invoice</a></li>` : ''}
                  </ul>
                </div>
            `;

            tr.innerHTML = `
                <td class="sticky-col sticky-date">${formatDate(item.invoice_date)}</td>
                <td class="sticky-col sticky-invno">${item.invoice_no}</td>
                <td class="sticky-col sticky-mrn">${item.mrn}</td>
                <td class="sticky-col sticky-patient">
                    <div class="font-weight-bold" style="color: var(--foreground);">${item.patient_name}</div>
                    <div style="font-size: 0.75rem; color: var(--muted-foreground);">${item.payer_name}</div>
                </td>
                <td class="sticky-col sticky-ref" style="border-right: 2px solid var(--border) !important;">${item.ref_no || '-'}</td>
                <td class="text-end">${formatCurrency(item.amount)}</td>
                <td class="text-center">-</td>
                <td class="text-center">-</td>
                <td class="text-center">-</td>
                <td class="text-end">${formatCurrency(item.paid_amount || 0)}</td>
                <td class="text-end font-weight-bold">${formatCurrency(displayBalance)}</td>
                <td>${formatDate(item.sent_date)}</td>
                <td>${item.courier_via || '-'}</td>
                <td>${item.tracking_no || '-'}</td>
                <td>${formatDate(item.received_date)}</td>
                <td class="text-center" style="border-right: 2px solid var(--border) !important;">
                    ${item.due_days > 0 ? `<span style="color: #059669; font-weight: bold;">${item.due_days}</span>` : '-'}
                </td>
                <td>
                    <div style="font-size: 0.8rem; max-width: 150px; white-space: normal; color: var(--muted-foreground);">
                        ${item.remarks ? item.remarks : '<span class="text-muted" style="opacity: 0.5;">No remarks</span>'}
                    </div>
                </td>
                <td>
                    <div class="d-flex flex-column align-items-start gap-1">
                        <span class="badge-status ${item.status}">${item.status}</span>
                        ${cancelFlagHtml}
                    </div>
                </td>
                <td>
                    <div class="d-flex flex-wrap gap-1">
                        ${actionButtons}
                    </div>
                </td>
            `;

            tableBody.appendChild(tr);
        });

        // Update Summary Row
        document.getElementById('tfCount').textContent = data.length;
        document.getElementById('tfAmount').textContent = formatCurrency(totalAmount);
        document.getElementById('tfPaid').textContent = formatCurrency(totalPaid);
        document.getElementById('tfBalance').textContent = formatCurrency(totalVal);

        recordCountDisplay.textContent = `Showing ${data.length} records`;
        totalAmountDisplay.textContent = `Total Balance: ${formatCurrency(totalVal)}`;

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
    //  EVENT LISTENERS
    // ===========================
    tableBody.addEventListener('click', (e) => {
        const itemEl = e.target.closest('.action-dd-item');
        if (!itemEl) return;
        e.preventDefault();

        const id = parseInt(itemEl.getAttribute('data-id'));
        const action = itemEl.getAttribute('data-action');
        const item = invoicesData.find(i => i.id === id);
        if (!item) return;

        if (action === 'SENT') {
            selectedIds.clear();
            selectedIds.add(id);
            document.getElementById('sdItemCount').textContent = '1';
            // Pre-fill ref_no if exists
            document.getElementById('inputRefNo').value = item.ref_no || '';
            sendDocModal?.show();
        } else if (action === 'RECEIVED') {
            selectedIds.clear();
            selectedIds.add(id);
            document.getElementById('rcItemCount').textContent = '1';
            setReceivedModal?.show();
        } else if (action === 'REMARKS') {
            selectedIds.clear();
            selectedIds.add(id);
            document.getElementById('rmItemCount').textContent = '1';
            document.getElementById('inputRemarks').value = item.remarks || '';
            remarksModal?.show();
        } else if (action === 'CANCEL') {
            selectedIds.clear();
            selectedIds.add(id);
            document.getElementById('ccItemCount').textContent = '1';
            document.getElementById('inputCancelReason').value = '';
            cancelInvModal?.show();
        } else {
            // For BATCHING, REVISE, PAID -> direct update
            invoicesData = invoicesData.map(inv => {
                if (inv.id === id) return { ...inv, status: action };
                return inv;
            });
            updateUI();
        }
    });

    payerFilter.addEventListener('change', () => renderTable());
    searchFilter.addEventListener('input', () => renderTable());
    dateFrom.addEventListener('change', () => renderTable());
    dateTo.addEventListener('change', () => renderTable());

    document.getElementById('resetBtn').addEventListener('click', () => {
        payerFilter.value = 'All';
        searchFilter.value = '';
        dateFrom.value = '';
        dateTo.value = '';
        activeTab = 'ALL';
        updateUI();
    });

    // ===========================
    //  MODAL ACTIONS
    // ===========================
    document.getElementById('btnSubmitSendDoc').addEventListener('click', () => {
        const refNo = document.getElementById('inputRefNo').value;
        const via = document.getElementById('inputVia').value;
        const tracking = document.getElementById('inputTrackingNo').value;
        const sDate = document.getElementById('inputSentDate').value;
        if (!tracking) return alert('Tracking Number is required');

        invoicesData = invoicesData.map(inv => {
            if (selectedIds.has(inv.id)) {
                return { ...inv, status: 'SENT', ref_no: refNo || inv.ref_no, courier_via: via, tracking_no: tracking, sent_date: sDate };
            }
            return inv;
        });

        sendDocModal?.hide();
        document.getElementById('inputTrackingNo').value = '';
        document.getElementById('inputRefNo').value = '';
        selectedIds.clear();
        updateUI();
    });

    document.getElementById('btnSubmitReceived').addEventListener('click', () => {
        const rDate = document.getElementById('inputReceivedDate').value;

        invoicesData = invoicesData.map(inv => {
            if (selectedIds.has(inv.id)) {
                return { ...inv, status: 'RECEIVED', received_date: rDate };
            }
            return inv;
        });

        setReceivedModal?.hide();
        selectedIds.clear();
        updateUI();
    });

    document.getElementById('btnSubmitRemarks').addEventListener('click', () => {
        const rmVal = document.getElementById('inputRemarks').value;

        invoicesData = invoicesData.map(inv => {
            if (selectedIds.has(inv.id)) {
                return { ...inv, remarks: rmVal };
            }
            return inv;
        });

        remarksModal?.hide();
        document.getElementById('inputRemarks').value = '';
        selectedIds.clear();
        updateUI();
    });

    document.getElementById('btnSubmitCancel').addEventListener('click', () => {
        const reason = document.getElementById('inputCancelReason').value;
        const today = new Date().toISOString().slice(0, 10);

        invoicesData = invoicesData.map(inv => {
            if (selectedIds.has(inv.id)) {
                return {
                    ...inv,
                    is_cancelled: true,
                    cancelled_date: today,
                    remarks: reason ? `[CANCELLED] ${reason}` : (inv.remarks ? `[CANCELLED] ${inv.remarks}` : '[CANCELLED]')
                };
            }
            return inv;
        });

        cancelInvModal?.hide();
        document.getElementById('inputCancelReason').value = '';
        selectedIds.clear();
        updateUI();
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
                    item.due_days || 0,
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
