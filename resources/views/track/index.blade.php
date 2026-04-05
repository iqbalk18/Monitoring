@extends('layouts.app')

@section('title', 'AR Tracking - Bali International Hospital')

@push('styles')
    <!-- Datatables CSS (using DataTables for standard table features if needed, but we'll try vanilla JS first for custom UI) -->
    <style>
        .filter-header {
            background-color: var(--card-bg, #fff);
            padding: 1.5rem;
            border-radius: var(--radius);
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            margin-bottom: 1.5rem;
        }

        .dark .filter-header {
            background-color: var(--card-bg, #1e293b);
            border: 1px solid var(--border-color, #334155);
        }

        .status-tabs {
            display: flex;
            gap: 0.5rem;
            overflow-x: auto;
            padding-bottom: 0.5rem;
            margin-bottom: 1.5rem;
            border-bottom: 2px solid var(--border-color, #e2e8f0);
        }

        .dark .status-tabs {
            border-bottom-color: var(--border-color, #334155);
        }

        .status-tab {
            padding: 0.5rem 1rem;
            border: none;
            background: none;
            color: var(--text-color, #64748b);
            font-weight: 500;
            cursor: pointer;
            white-space: nowrap;
            position: relative;
            transition: all 0.2s;
        }

        .status-tab:hover {
            color: var(--heading-color, #0f172a);
        }

        .dark .status-tab:hover {
            color: var(--heading-color, #f8fafc);
        }

        .status-tab.active {
            color: var(--primary-color, #0ea5e9);
        }

        .status-tab.active::after {
            content: '';
            position: absolute;
            bottom: -2.5px;
            left: 0;
            right: 0;
            height: 2px;
            background-color: var(--primary-color, #0ea5e9);
            border-radius: 2px 2px 0 0;
        }

        .grid-container {
            background-color: var(--card-bg, #fff);
            border-radius: var(--radius);
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            overflow: hidden;
            margin-bottom: 5rem; /* space for sticky footer */
        }

        .dark .grid-container {
            background-color: var(--card-bg, #1e293b);
            border: 1px solid var(--border-color, #334155);
        }

        .table-responsive {
            overflow-x: auto;
        }

        .table-shadcn {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        .table-shadcn th,
        .table-shadcn td {
            padding: 0.5rem 1rem;
            border-bottom: 1px solid var(--border-color, #e2e8f0);
            white-space: nowrap;
        }

        .dark .table-shadcn th,
        .dark .table-shadcn td {
            border-bottom-color: var(--border-color, #334155);
        }

        .table-shadcn th {
            font-weight: 600;
            padding-top: 0.5rem;
            padding-bottom: 0.5rem;
            color: var(--heading-color, #334155);
            background-color: var(--background-color, #f8fafc);
        }

        .dark .table-shadcn th {
            color: var(--heading-color, #cbd5e1);
            background-color: var(--background-color, #0f172a);
        }

        .table-shadcn tbody tr:hover {
            background-color: var(--background-color, #f1f5f9);
            cursor: pointer;
        }

        .dark .table-shadcn tbody tr:hover {
            background-color: var(--background-color, #0f172a);
            cursor: pointer;
        }

        /* Sticky Action Footer */
        .sticky-action-footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: var(--card-bg, #fff);
            border-top: 1px solid var(--border-color, #e2e8f0);
            padding: 1rem;
            box-shadow: 0 -4px 6px -1px rgba(0, 0, 0, 0.1);
            transform: translateY(100%);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            justify-content: space-between;
            z-index: 50;
        }

        .dark .sticky-action-footer {
            background-color: var(--card-bg, #1e293b);
            border-top-color: var(--border-color, #334155);
            box-shadow: 0 -4px 6px -1px rgba(0, 0, 0, 0.5);
        }

        .sticky-action-footer.active {
            transform: translateY(0);
        }

        .badge-status {
            padding: 0.25rem 0.6rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .badge-status.BATCHING { background-color: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; }
        .badge-status.SENT { background-color: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
        .badge-status.RECEIVED { background-color: #fef9c3; color: #a16207; border: 1px solid #fef08a; }
        .badge-status.REVISE { background-color: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }
        .badge-status.PAID { background-color: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }

        .dark .badge-status.BATCHING { background-color: #334155; color: #cbd5e1; border-color: #475569; }
        .dark .badge-status.SENT { background-color: #1e3a8a; color: #93c5fd; border-color: #1d4ed8; }
        .dark .badge-status.RECEIVED { background-color: #713f12; color: #fde047; border-color: #a16207; }
        .dark .badge-status.REVISE { background-color: #7f1d1d; color: #fca5a5; border-color: #dc2626; }
        .dark .badge-status.PAID { background-color: #14532d; color: #86efac; border-color: #15803d; }

        .hidden-col { display: none; }

        /* Table Scrolling Setup */
        .table-container {
            width: 100%;
            overflow-x: auto;
            /* smooth scrolling on iOS */
            -webkit-overflow-scrolling: touch;
        }

        .table-shadcn {
            min-width: 1200px; /* Force scroll if screen is too narrow */
        }

        /* Modal Dark Mode Fixes */
        .dark .modal-content {
            background-color: #1e293b;
            color: #f8fafc;
            border: 1px solid #334155;
        }
        .dark .modal-header, .dark .modal-footer {
            border-color: #334155;
        }
        .dark .form-control, .dark .form-select {
            background-color: #0f172a;
            color: #f8fafc;
            border-color: #334155;
        }
        .dark .form-control:focus, .dark .form-select:focus {
            background-color: #0f172a;
            border-color: #0ea5e9;
            color: #f8fafc;
        }
        .dark .btn-close {
            filter: invert(1) grayscale(100%) brightness(200%);
        }
        .dark .text-muted {
            color: #94a3b8 !important;
        }

        /* Sticky Columns */
        .sticky-col {
            position: sticky !important;
            background-color: var(--background-color, #f8fafc);
            z-index: 2;
        }
        
        .dark .sticky-col {
            background-color: var(--background-color, #0f172a);
        }

        /* tbody cells need to use card-bg to look seamless on scroll */
        tbody .sticky-col {
            background-color: var(--card-bg, #fff);
        }

        .dark tbody .sticky-col {
            background-color: var(--card-bg, #1e293b);
        }

        /* Summary Footer */
        tfoot {
            position: sticky;
            bottom: 0;
            z-index: 3;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.05);
        }
        
        tfoot td {
            background-color: #f1f5f9 !important; /* light gray as requested */
            border-top: 2px solid #cbd5e1 !important;
            border-bottom: none !important;
            padding-top: 0.5rem;
            padding-bottom: 0.5rem;
        }

        .dark tfoot td {
            background-color: #334155 !important;
            border-top-color: #475569 !important;
        }

        /* Summary Sticky Columns require higher z-index so they don't scroll under other columns in the footer */
        tfoot .sticky-col {
            z-index: 5 !important;
        }

        /* Adjust these left values based on actual width rendering 
           or use Javascript to calculate width, but CSS calc is simpler for fixed layout */
        .sticky-checkbox { left: 0; min-width: 40px; }
        .sticky-date { left: 40px; min-width: 90px; }
        .sticky-invno { left: 130px; min-width: 90px; }
        .sticky-patient { left: 220px; min-width: 180px; }
        .sticky-mrn { left: 400px; min-width: 90px; }
        .sticky-ref { left: 490px; min-width: 110px; border-right: 2px solid var(--border-color, #e2e8f0); }
        
        .dark .sticky-ref { border-right-color: var(--border-color, #334155); }

    </style>
@endpush

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800" style="color: var(--heading-color); font-weight: 700;">AR Tracking</h1>
            <p class="text-muted" style="color: var(--text-color);">Monitor accounts receivable document statuses.</p>
        </div>
    </div>

    <!-- Filter Header -->
    <div class="filter-header">
        <div class="row g-3">
            <div class="col-md-4">
                <label for="payerFilter" class="form-label" style="font-size: 0.875rem; font-weight: 500; color: var(--heading-color);">Filter per Payer</label>
                <select id="payerFilter" class="form-select" style="background-color: var(--background-color); border-color: var(--border-color); color: var(--text-color);">
                    <option value="All">All Payers</option>
                    <option value="Global Excel">Global Excel</option>
                    <option value="Admedika">Admedika</option>
                </select>
            </div>
            <div class="col-md-5">
                <label for="searchFilter" class="form-label" style="font-size: 0.875rem; font-weight: 500; color: var(--heading-color);">Search</label>
                <div class="input-group">
                    <span class="input-group-text" style="background-color: var(--background-color); border-color: var(--border-color); color: var(--text-color);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    </span>
                    <input type="text" id="searchFilter" class="form-control" placeholder="Patient Name, MRN, Inv No..." style="background-color: var(--background-color); border-color: var(--border-color); color: var(--text-color);">
                </div>
            </div>
            <div class="col-md-3 d-flex align-items-end justify-content-end gap-2">
                 <button class="btn-shadcn btn-shadcn-outline w-100" id="resetBtn">
                     Reset
                 </button>
                 <button class="btn-shadcn btn-shadcn-primary w-100" id="btnExportExcel">
                     Export Excel
                 </button>
            </div>
        </div>
    </div>


    <!-- Data Grid -->
    <div class="grid-container">
        <div class="table-container">
            <table class="table-shadcn" id="dataTable">
                <thead>
                    <tr>
                        <th class="sticky-col sticky-date" rowspan="2" style="background-color: var(--background-color, #f8fafc); z-index: 5;">Inv Dat</th>
                        <th class="sticky-col sticky-invno" rowspan="2" style="background-color: var(--background-color, #f8fafc); z-index: 5;">Inv No</th>
                        <th class="sticky-col sticky-mrn" rowspan="2" style="background-color: var(--background-color, #f8fafc); z-index: 5;">MRN</th>
                        <th class="sticky-col sticky-patient" rowspan="2" style="background-color: var(--background-color, #f8fafc); z-index: 5;">Patient Name</th>
                        <th class="sticky-col sticky-ref" rowspan="2" style="border-right: 2px solid var(--border-color, #e2e8f0) !important; background-color: var(--background-color, #f8fafc); z-index: 5;">Ref No</th>
                        <th colspan="11" class="text-center" style="border-bottom: 1px solid var(--border-color, #e2e8f0); border-right: 2px solid var(--border-color, #e2e8f0) !important;">💰 Section: Tracking</th>
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
                        <th style="border-right: 2px solid var(--border-color, #e2e8f0) !important;">Due</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <!-- JS will populate rows -->
                </tbody>
                <tfoot id="tableFooter">
                    <tr>
                        <td class="sticky-col sticky-date text-center font-weight-bold" id="tfCount">0</td>
                        <td class="sticky-col sticky-invno"></td>
                        <td class="sticky-col sticky-mrn"></td>
                        <td class="sticky-col sticky-patient text-end font-weight-bold" style="padding-right: 1.5rem;">Grand Total</td>
                        <td class="sticky-col sticky-ref" style="border-right: 2px solid var(--border-color, #cbd5e1) !important;"></td>
                        <td class="text-end font-weight-bold" style="color: var(--heading-color);" id="tfAmount">0</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="text-end font-weight-bold" style="color: var(--heading-color);" id="tfPaid">0</td>
                        <td class="text-end font-weight-bold" style="color: #059669;" id="tfBalance">0</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td style="border-right: 2px solid var(--border-color, #cbd5e1) !important;"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="d-flex justify-content-between align-items-center p-3 border-top" style="border-top-color: var(--border-color) !important;">
            <div class="text-sm" style="color: var(--text-color);" id="recordCount">
                Showing 0 records
            </div>
            <div class="text-sm font-weight-bold" style="color: var(--heading-color);" id="totalAmount">
                Total Balance: 0
            </div>
        </div>
    </div>

    <!-- Removed Sticky Action Footer -->
    
    <!-- Modals -->
    <!-- Cover Note Modal -->
    <div class="modal fade" id="coverNoteModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title font-weight-bold">Create Cover Note</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small mb-3">You are setting <span id="cnItemCount">0</span> invoices to COVER_NOTE status.</p>
                    <div class="mb-3">
                        <label class="form-label font-weight-bold small">Ref No / Cover Note No</label>
                        <input type="text" class="form-control" id="inputRefNo" placeholder="e.g. 21-JKT044811" required>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn-shadcn btn-shadcn-ghost" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn-shadcn btn-shadcn-primary" id="btnSubmitCoverNote">Update Status</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Send Document Modal -->
    <div class="modal fade" id="sendDocModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title font-weight-bold">Send Document</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small mb-3">You are dispatching <span id="sdItemCount">0</span> invoices (Status: SENT).</p>
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
                    <p class="text-muted small mb-3">Mark <span id="rcItemCount">0</span> invoices as RECEIVED by the Payer.</p>
                    <div class="mb-3">
                        <label class="form-label font-weight-bold small">Received Date</label>
                        <input type="date" class="form-control" id="inputReceivedDate" value="{{ date('Y-m-d') }}" required>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn-shadcn btn-shadcn-ghost" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn-shadcn" style="background-color: #6d28d9; color: white;" id="btnSubmitReceived">Update to RECEIVED</button>
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
                    <p class="text-muted small mb-3">Update remarks for <span id="rmItemCount">0</span> invoices.</p>
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

@endsection

@push('scripts')
<!-- SheetJS Library for Excel Export -->
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
<script>
    // Dummy Data Injected from Controller
    // In a real scenario, this would be empty initially and fetched via API
    let invoicesData = @json($dummyInvoices);

    const formatCurrency = (amount) => {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(amount);
    };

    const formatDate = (dateString) => {
        if (!dateString) return '-';
        const date = new Date(dateString);
        return date.toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: '2-digit' }).replace(/ /g, '-');
    };

    let selectedIds = new Set();
    
    // DOM Elements
    const tableBody = document.getElementById('tableBody');
    const statusTabsContainer = document.getElementById('statusTabs');
    const payerFilter = document.getElementById('payerFilter');
    const searchFilter = document.getElementById('searchFilter');
    const recordCountDisplay = document.getElementById('recordCount');
    const totalAmountDisplay = document.getElementById('totalAmount');
    
    const btnExportExcel = document.getElementById('btnExportExcel');

    // Modals
    const coverNoteModalElement = document.getElementById('coverNoteModal');
    const sendDocModalElement = document.getElementById('sendDocModal');
    const setReceivedModalElement = document.getElementById('setReceivedModal');
    const remarksModalElement = document.getElementById('remarksModal');
    
    // Check if Bootstrap is loaded
    let coverNoteModal, sendDocModal, setReceivedModal, remarksModal;
    if (typeof bootstrap !== 'undefined') {
        coverNoteModal = new bootstrap.Modal(coverNoteModalElement);
        sendDocModal = new bootstrap.Modal(sendDocModalElement);
        setReceivedModal = new bootstrap.Modal(setReceivedModalElement);
        remarksModal = new bootstrap.Modal(remarksModalElement);
    }
    

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



    const getFilteredData = () => {
        const payerVal = payerFilter.value;
        const searchVal = searchFilter.value.toLowerCase();

        return invoicesData.filter(item => {
            const matchPayer = payerVal === 'All' || item.payer_name === payerVal;
            const matchSearch = searchVal === '' || 
                                item.patient_name.toLowerCase().includes(searchVal) ||
                                item.mrn.toLowerCase().includes(searchVal) ||
                                item.invoice_no.toLowerCase().includes(searchVal);
            return matchPayer && matchSearch;
        });
    };

    const renderTable = () => {
        const data = getFilteredData();
        tableBody.innerHTML = '';
        
        let totalVal = 0;
        let totalAmount = 0;
        let totalPaid = 0;

        if (data.length === 0) {
            tableBody.innerHTML = `<tr><td colspan="17" class="text-center py-5 text-muted">No records found matching the criteria.</td></tr>`;
            recordCountDisplay.textContent = 'Showing 0 records';
            totalAmountDisplay.textContent = 'Total Balance: IDR 0';
            
            // reset summary line
            document.getElementById('tfCount').textContent = '0';
            document.getElementById('tfAmount').textContent = formatCurrency(0);
            document.getElementById('tfPaid').textContent = formatCurrency(0);
            document.getElementById('tfBalance').textContent = formatCurrency(0);

            return;
        }

        data.forEach(item => {
            totalVal += item.balance;
            totalAmount += item.amount;
            totalPaid += (item.paid_amount || 0);
            
            const tr = document.createElement('tr');
            
            let actionButtons = `
                <div class="dropdown">
                  <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="font-size: 0.75rem;">
                    Update Status
                  </button>
                  <ul class="dropdown-menu shadow-sm" style="font-size: 0.875rem;">
                    <li><a class="dropdown-item action-dd-item" href="javascript:void(0)" data-id="${item.id}" data-action="BATCHING">Set Batching</a></li>
                    <li><a class="dropdown-item action-dd-item" href="javascript:void(0)" data-id="${item.id}" data-action="SENT">Set Sent</a></li>
                    <li><a class="dropdown-item action-dd-item" href="javascript:void(0)" data-id="${item.id}" data-action="RECEIVED">Set Received</a></li>
                    <li><a class="dropdown-item action-dd-item" href="javascript:void(0)" data-id="${item.id}" data-action="REVISE">Set Revise</a></li>
                    <li><a class="dropdown-item action-dd-item" href="javascript:void(0)" data-id="${item.id}" data-action="PAID">Set Paid</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item action-dd-item" href="javascript:void(0)" data-id="${item.id}" data-action="REMARKS">Update Remarks</a></li>
                  </ul>
                </div>
            `;

            tr.innerHTML = `
                <td class="sticky-col sticky-date">${formatDate(item.invoice_date)}</td>
                <td class="sticky-col sticky-invno">${item.invoice_no}</td>
                <td class="sticky-col sticky-mrn">${item.mrn}</td>
                <td class="sticky-col sticky-patient">
                    <div class="font-weight-bold" style="color: var(--heading-color);">${item.patient_name}</div>
                    <div style="font-size: 0.75rem; color: var(--text-color);">${item.payer_name}</div>
                </td>
                <td class="sticky-col sticky-ref" style="border-right: 2px solid var(--border-color, #e2e8f0) !important;">${item.ref_no || '-'}</td>
                <td class="text-end">${formatCurrency(item.amount)}</td>
                <td class="text-center">-</td>
                <td class="text-center">-</td>
                <td class="text-center">-</td>
                <td class="text-end">${formatCurrency(item.paid_amount || 0)}</td>
                <td class="text-end font-weight-bold" style="color: var(--heading-color);">${formatCurrency(item.balance)}</td>
                <td>${formatDate(item.sent_date)}</td>
                <td>${item.courier_via || '-'}</td>
                <td>${item.tracking_no || '-'}</td>
                <td>${formatDate(item.received_date)}</td>
                <td class="text-center" style="border-right: 2px solid var(--border-color, #e2e8f0) !important;">
                    ${item.due_days > 0 ? `<span style="color: #059669; font-weight: bold;">${item.due_days}</span>` : '-'}
                </td>
                <td>
                    <div style="font-size: 0.8rem; max-width: 150px; white-space: normal; color: var(--text-color);">
                        ${item.remarks ? item.remarks : '<span class="text-muted" style="opacity: 0.5;">No remarks</span>'}
                    </div>
                </td>
                <td>
                    <span class="badge-status ${item.status}">${item.status.replace('_', ' ')}</span>
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

    // Event Listeners
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
        } else {
            // For BATCHING, REVISE, PAID -> direct update
            invoicesData = invoicesData.map(inv => {
                if (inv.id === id) return { ...inv, status: action };
                return inv;
            });
            renderTable();
        }
    });

    payerFilter.addEventListener('change', () => {
        renderTable();
    });

    searchFilter.addEventListener('input', () => {
        renderTable();
    });

    document.getElementById('resetBtn').addEventListener('click', () => {
        payerFilter.value = 'All';
        searchFilter.value = '';
        renderTable();
    });

    // Mock Backend Operations
    document.getElementById('btnSubmitCoverNote').addEventListener('click', () => {
        const refNo = document.getElementById('inputRefNo').value;
        if (!refNo) return alert('Ref No is required');

        invoicesData = invoicesData.map(inv => {
            if (selectedIds.has(inv.id)) {
                return { ...inv, status: 'COVER_NOTE', ref_no: refNo };
            }
            return inv;
        });

        coverNoteModal?.hide();
        document.getElementById('inputRefNo').value = '';
        selectedIds.clear();
        renderTable();
    });

    document.getElementById('btnSubmitSendDoc').addEventListener('click', () => {
        const via = document.getElementById('inputVia').value;
        const tracking = document.getElementById('inputTrackingNo').value;
        const sDate = document.getElementById('inputSentDate').value;
        if (!tracking) return alert('Tracking Number is required');

        invoicesData = invoicesData.map(inv => {
            if (selectedIds.has(inv.id)) {
                return { ...inv, status: 'SENT', courier_via: via, tracking_no: tracking, sent_date: sDate };
            }
            return inv;
        });

        sendDocModal?.hide();
        document.getElementById('inputTrackingNo').value = '';
        selectedIds.clear();
        renderTable();
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
        renderTable();
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
        selectedIds.clear(); // User choice whether clearing selection is better after remarks
        renderTable();
    });

        // Add Export Excel logic (Raw Database Format)
        const exportExcel = () => {
            if (typeof XLSX === 'undefined') {
                alert('Excel library is still loading, please wait a moment.');
                return;
            }

            const wb = XLSX.utils.book_new();

            // Export logic: Iterate over every defined tab (Show All, Paid All, OS All, etc.)
            // and create a separate sheet for each one containing the raw "Database" structure.
            tabsDef.forEach(tab => {
                // Filter data based on the tab's specific logic query
                const tabData = invoicesData.filter(item => tab.query(item));
                
                let aoa = [];
                
                // Define the exact raw database column names as headers
                const dbHeaders = [
                    'id',
                    'payer_name',
                    'patient_name',
                    'mrn',
                    'invoice_no',
                    'invoice_date',
                    'amount',
                    'paid_amount',
                    'balance',
                    'due_days',
                    'status',
                    'ref_no',
                    'courier_via',
                    'tracking_no',
                    'sent_date',
                    'received_date',
                    'paid_on',
                    'remarks'
                ];
                
                aoa.push(dbHeaders);

                // Populate rows with raw data explicitly matching the dbHeaders keys
                tabData.forEach(item => {
                    const row = [
                        item.id,
                        item.payer_name || '',
                        item.patient_name || '',
                        item.mrn || '',
                        item.invoice_no || '',
                        item.invoice_date || '', // raw date string YYYY-MM-DD
                        item.amount || 0,
                        item.paid_amount || 0,
                        item.balance || 0,
                        item.due_days || 0,
                        item.status || '',
                        item.ref_no || '',
                        item.courier_via || '',
                        item.tracking_no || '',
                        item.sent_date || '',
                        item.received_date || '',
                        item.paid_on || '',
                        item.remarks || ''
                    ];
                    aoa.push(row);
                });

                // Create Worksheet and append it to our Workbook
                const ws = XLSX.utils.aoa_to_sheet(aoa);
                // Excel Sheet names max 31 characters and cannot contain certain symbols
                const safeSheetName = tab.label.replace(/[\[\]\*\?\/\\:]/g, "").substring(0, 31);
                
                XLSX.utils.book_append_sheet(wb, ws, safeSheetName);
            });
            
            // Name the file dynamically based on the current timestamp
            const fileName = `AR_Tracking_Full_DB_Export_${new Date().toISOString().slice(0,10)}.xlsx`;
            XLSX.writeFile(wb, fileName);
        };

        if (btnExportExcel) {
            btnExportExcel.addEventListener('click', exportExcel);
        }

    // Initial Render
    renderTable();
    updateUI();
</script>
@endpush
