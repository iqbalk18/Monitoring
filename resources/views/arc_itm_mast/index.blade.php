@extends('layouts.app')

@section('title', 'ARC Item Master - Bali International Hospital')

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
    /* Hide empty top controls wrapper */
    .dataTables_wrapper > .row:first-child,
    .dataTables_wrapper > .row:has(.dataTables_length):not(:has(.dataTables_info)),
    .dataTables_wrapper > div:first-child:not(.table-container-shadcn):not(table) {
        display: none !important;
    }
    .dataTables_wrapper {
        margin-top: 0 !important;
        padding-top: 0 !important;
    }
    .dataTables_wrapper table,
    .dataTables_wrapper > .row:has(table) {
        margin-top: 0 !important;
    }
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter {
        margin-bottom: 0 !important;
    }
    /* Compact shadcn pagination */
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
    
    /* Hide length menu from top row */
    .dataTables_wrapper > .row:first-child .dataTables_length {
        display: none !important;
    }
    
    /* Bottom wrapper */
    .dataTables_wrapper .dataTables_info {
        padding-top: 0 !important;
        display: inline-flex !important;
        align-items: center !important;
    }
    .dataTables_wrapper > .row:last-child {
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        padding: 0.75rem 1rem !important;
        border-top: 1px solid var(--border) !important;
        margin: 0 !important;
        flex-wrap: nowrap !important;
    }
    .dataTables_wrapper > .row:last-child > div {
        width: auto !important;
        max-width: none !important;
        flex: none !important;
        padding: 0 !important;
    }
    
    /* Left side container for info + length */
    .dataTables_wrapper > .row:last-child > div:first-child {
        display: flex !important;
        align-items: center !important;
        gap: 1rem !important;
    }
    
    /* Cloned length menu beside info */
    .dataTables_wrapper .bottom-length-menu {
        display: inline-flex !important;
        align-items: center !important;
        float: none !important;
    }
    .dataTables_wrapper .bottom-length-menu label {
        margin-bottom: 0 !important;
    }
</style>
@endpush

@section('content')
<!-- Page Header -->
<div class="flex-between mb-4" style="flex-wrap: wrap; gap: 1rem;">
    <div>
        <h2 class="section-title">ARC Item Master</h2>
        <p class="section-desc">Manage master data items and pricing configuration.</p>
    </div>
    <div class="d-flex align-items-center" style="gap: 0.5rem;">
        <a href="{{ route('margin.index') }}" class="btn-shadcn btn-shadcn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><path d="m19 9-5 5-4-4-3 3"/></svg>
            Kelola Margin
        </a>
    </div>
</div>

<!-- Alerts -->
<div id="alertContainer">
    @if(session('success'))
    <div class="alert-shadcn alert-shadcn-success" role="alert">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        <div>
            <div class="alert-title">Success</div>
            <div class="alert-description">{{ session('success') }}</div>
        </div>
    </div>
    @endif
    @if($errors->any())
    <div class="alert-shadcn alert-shadcn-destructive" role="alert">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" x2="9" y1="9" y2="15"/><line x1="9" x2="15" y1="9" y2="15"/></svg>
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

<!-- Search/Filter Section -->
<div class="card-shadcn mb-4">
    <div class="card-shadcn-body">
        <form method="GET" action="{{ route('arc-itm-mast.index') }}">
            <div class="d-flex flex-wrap align-items-center" style="gap: 0.75rem;">
                <div class="flex-grow-1">
                    <input type="text" name="search" class="form-control-shadcn" placeholder="Search by Code, Description, Category..." value="{{ request('search') }}" style="width: 100%;">
                </div>
                <div>
                    <select name="status" class="form-select-shadcn" style="width: 150px;">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="non_active" {{ request('status') == 'non_active' ? 'selected' : '' }}>Non Active</option>
                    </select>
                </div>
                <button type="submit" class="btn-shadcn btn-shadcn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                    Search
                </button>
                @if(request('search') || request('status'))
                <a href="{{ route('arc-itm-mast.index') }}" class="btn-shadcn btn-shadcn-outline">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/><path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/><path d="M8 16H3v5"/></svg>
                    Reset
                </a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Data Table -->
<div class="card-shadcn">
    <div class="card-shadcn-header flex-between">
        <div class="d-flex align-items-center" style="gap: 0.75rem;">
            <h3 class="card-shadcn-title mb-0">Item Master Data</h3>
            <span class="badge-shadcn badge-shadcn-secondary">{{ $items->total() }} records</span>
        </div>
        <div class="d-flex align-items-center" style="gap: 0.5rem;">
            <input type="text" id="customSearch" class="form-control-shadcn" placeholder="Quick filter..." style="width: 200px; height: 32px;">
        </div>
    </div>
    <div class="card-shadcn-body" style="padding: 0;">
        <div class="table-container-shadcn" style="border: none; border-radius: 0;">
            <table id="arcTable" class="table-shadcn" style="width: 100%;">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>ARCIM Code</th>
                        <th>ARCIM Description</th>
                        <th>Serv/Material</th>
                        <th>ARCIC Desc</th>
                        <th>ORCAT Desc</th>
                        <th>ARCSG Desc</th>
                        <th>ARCBG Desc</th>
                        <th>Order On Its Own</th>
                        <th>Reorder On Its Own</th>
                        <th>Eff Date</th>
                        <th>Eff Date To</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $index => $item)
                    <tr>
                        <td>{{ $items->firstItem() + $index }}</td>
                        <td><code style="font-size: 0.8125rem;">{{ $item->ARCIM_Code ?? '-' }}</code></td>
                        <td>{{ Str::limit($item->ARCIM_Desc, 40) ?? '-' }}</td>
                        <td>{{ $item->ARCIM_ServMaterial ?? '-' }}</td>
                        <td>{{ Str::limit($item->ARCIC_Desc, 25) ?? '-' }}</td>
                        <td>{{ Str::limit($item->ORCAT_Desc, 25) ?? '-' }}</td>
                        <td>{{ Str::limit($item->ARCSG_Desc, 25) ?? '-' }}</td>
                        <td>{{ Str::limit($item->ARCBG_Desc, 25) ?? '-' }}</td>
                        <td>{{ $item->ARCIM_OrderOnItsOwn ?? '-' }}</td>
                        <td>{{ $item->ARCIM_ReorderOnItsOwn ?? '-' }}</td>
                        <td>{{ $item->ARCIM_EffDate ? $item->ARCIM_EffDate->format('d/m/Y') : '-' }}</td>
                        <td>{{ $item->ARCIM_EffDateTo ? $item->ARCIM_EffDateTo->format('d/m/Y') : '-' }}</td>
                        <td>
                            @php
                                $today = now()->startOfDay();
                                $isActive = is_null($item->ARCIM_EffDateTo) || $item->ARCIM_EffDateTo >= $today;
                            @endphp
                            @if($isActive)
                                <span class="badge-shadcn badge-shadcn-success">Active</span>
                            @else
                                <span class="badge-shadcn badge-shadcn-secondary">Non Active</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex align-items-center" style="gap: 0.375rem;">
                                <a href="{{ route('arc-itm-mast.edit', $item->id) }}" class="btn-shadcn btn-shadcn-outline btn-shadcn-sm" title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                                    Edit
                                </a>
                                <a href="{{ route('arc-item-price-italy.manage', $item->ARCIM_Code) }}" class="btn-shadcn btn-shadcn-secondary btn-shadcn-sm" title="Manage Price">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8"/><path d="M12 18V6"/></svg>
                                    Price
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="14" class="text-center" style="padding: 3rem;">
                            <div style="color: var(--muted-foreground);">
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" style="opacity: 0.5; margin-bottom: 1rem;"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><line x1="3" x2="21" y1="9" y2="9"/><line x1="9" x2="9" y1="21" y2="9"/></svg>
                                <p class="mb-0" style="font-size: 0.875rem;">No data found</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($items->hasPages())
    <div class="card-shadcn-footer">
        <div class="d-flex align-items-center justify-content-between">
            <div style="font-size: 0.875rem; color: var(--muted-foreground);">
                Showing {{ $items->firstItem() }}-{{ $items->lastItem() }} of {{ $items->total() }} records
            </div>
            <div>
                {{ $items->withQueryString()->links() }}
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
        // Initialize DataTable for client-side filtering only (not paging - use Laravel pagination)
        var table = $('#arcTable').DataTable({
            paging: false,
            ordering: true,
            info: false,
            searching: true,
            language: {
                search: "",
                searchPlaceholder: "Search..."
            }
        });

        // Custom search handler
        $('#customSearch').on('keyup', function() {
            table.search($(this).val()).draw();
        });
    });
</script>
@endpush