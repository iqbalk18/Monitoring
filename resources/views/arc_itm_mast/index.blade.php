@extends('layouts.app')

@section('title', 'ARC Item Master - Bali International Hospital')

@push('styles')
    <style>
        /* Simple table sorting indicators */
        #arcTable thead th {
            position: relative;
            user-select: none;
            cursor: pointer;
            transition: background-color 0.15s ease;
        }

        #arcTable thead th:hover {
            background-color: var(--muted);
        }

        #arcTable thead th.sorted-asc::after,
        #arcTable thead th.sorted-desc::after {
            content: '';
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            border: 5px solid transparent;
        }

        #arcTable thead th.sorted-asc::after {
            border-bottom-color: currentColor;
            border-top-width: 0;
            margin-top: -3px;
        }

        #arcTable thead th.sorted-desc::after {
            border-top-color: currentColor;
            border-bottom-width: 0;
            margin-top: 3px;
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
            @if(session('user') && session('user')['role'] !== 'PRICE_ENTRY')
                <a href="{{ route('margin.index') }}" class="btn-shadcn btn-shadcn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 3v18h18" />
                        <path d="m19 9-5 5-4-4-3 3" />
                    </svg>
                    Manage Margin
                </a>
            @endif
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

    <!-- Search/Filter Section -->
    <div class="card-shadcn mb-4">
        <div class="card-shadcn-body">
            <form method="GET" action="{{ route('arc-itm-mast.index') }}">
                <div class="d-flex flex-wrap align-items-center" style="gap: 0.75rem;">
                    <div class="flex-grow-1">
                        <input type="text" name="search" class="form-control-shadcn"
                            placeholder="Search by Code, Description, Category..." value="{{ request('search') }}"
                            style="width: 100%;">
                    </div>
                    <div>
                        <select name="status" class="form-select-shadcn" style="width: 150px;">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status', 'active') == 'active' ? 'selected' : '' }}>Active
                            </option>
                            <option value="non_active" {{ request('status') == 'non_active' ? 'selected' : '' }}>Non Active
                            </option>
                        </select>
                    </div>
                    <button type="submit" class="btn-shadcn btn-shadcn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8" />
                            <path d="m21 21-4.3-4.3" />
                        </svg>
                        Search
                    </button>
                    @if(request('search') || request()->has('status'))
                        <a href="{{ route('arc-itm-mast.index') }}" class="btn-shadcn btn-shadcn-outline">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8" />
                                <path d="M21 3v5h-5" />
                                <path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16" />
                                <path d="M8 16H3v5" />
                            </svg>
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
                <input type="text" id="customSearch" class="form-control-shadcn" placeholder="Quick filter..."
                    style="width: 200px; height: 32px;">
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
                            <th>Type of Item Code</th>
                            <th>Type of Item Desc</th>
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
                                <td>{{ $item->TypeofItemCode ?? '-' }}</td>
                                <td>{{ $item->TypeofItemDesc ?? '-' }}</td>
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
                                        @if(session('user') && session('user')['role'] !== 'PRICE_ENTRY')
                                            <a href="{{ route('arc-itm-mast.edit', $item->id) }}"
                                                class="btn-shadcn btn-shadcn-outline btn-shadcn-sm" title="Edit">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z" />
                                                    <path d="m15 5 4 4" />
                                                </svg>
                                                Edit
                                            </a>
                                        @endif
                                        <a href="{{ route('arc-item-price-italy.manage', $item->ARCIM_Code) }}"
                                            class="btn-shadcn btn-shadcn-secondary btn-shadcn-sm" title="Manage Price">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <circle cx="12" cy="12" r="10" />
                                                <path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8" />
                                                <path d="M12 18V6" />
                                            </svg>
                                            Price
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="14" class="text-center" style="padding: 3rem;">
                                    <div style="color: var(--muted-foreground);">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round"
                                            stroke-linejoin="round" style="opacity: 0.5; margin-bottom: 1rem;">
                                            <rect width="18" height="18" x="3" y="3" rx="2" ry="2" />
                                            <line x1="3" x2="21" y1="9" y2="9" />
                                            <line x1="9" x2="9" y1="21" y2="9" />
                                        </svg>
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

    <script>
        $(document).ready(function () {
            // Simple client-side filtering
            $('#customSearch').on('keyup', function () {
                var value = $(this).val().toLowerCase();

                $('#arcTable tbody tr').each(function () {
                    // Skip empty state row (with colspan)
                    if ($(this).find('td[colspan]').length > 0) {
                        return;
                    }

                    var text = $(this).text().toLowerCase();
                    $(this).toggle(text.indexOf(value) > -1);
                });

                // Show/hide "no results" message
                var visibleRows = $('#arcTable tbody tr:visible').not(':has(td[colspan])').length;
                if (visibleRows === 0 && value !== '') {
                    if ($('#noFilterResults').length === 0) {
                        $('#arcTable tbody').append(`
                                        <tr id="noFilterResults">
                                            <td colspan="14" class="text-center" style="padding: 2rem;">
                                                <div style="color: var(--muted-foreground);">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" style="opacity: 0.5; margin-bottom: 0.5rem;"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                                                    <p class="mb-0" style="font-size: 0.875rem;">No results found for "${value}"</p>
                                                </div>
                                            </td>
                                        </tr>
                                    `);
                    }
                } else {
                    $('#noFilterResults').remove();
                }
            });

            // Table sorting (click-to-sort on headers)
            $('#arcTable thead th').on('click', function () {
                var table = $(this).parents('table').eq(0);
                var rows = table.find('tbody tr').not(':has(td[colspan])').toArray().sort(comparer($(this).index()));
                this.asc = !this.asc;

                if (!this.asc) {
                    rows = rows.reverse();
                }

                for (var i = 0; i < rows.length; i++) {
                    table.find('tbody').append(rows[i]);
                }

                // Update sort indicator
                $('#arcTable thead th').removeClass('sorted-asc sorted-desc');
                $(this).addClass(this.asc ? 'sorted-asc' : 'sorted-desc');
            });

            function comparer(index) {
                return function (a, b) {
                    var valA = getCellValue(a, index);
                    var valB = getCellValue(b, index);
                    return $.isNumeric(valA) && $.isNumeric(valB) ?
                        valA - valB : valA.toString().localeCompare(valB);
                };
            }

            function getCellValue(row, index) {
                return $(row).children('td').eq(index).text();
            }
        });
    </script>
@endpush