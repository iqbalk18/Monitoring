@extends('layouts.app')

@section('title', 'Margin - Bali International Hospital')

@push('styles')
    <style>
        /* Simple table sorting indicators */
        #marginTable thead th {
            position: relative;
            user-select: none;
            cursor: pointer;
            transition: background-color 0.15s ease;
        }

        #marginTable thead th:hover {
            background-color: var(--muted);
        }

        #marginTable thead th.sorted-asc::after,
        #marginTable thead th.sorted-desc::after {
            content: '';
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            border: 5px solid transparent;
        }

        #marginTable thead th.sorted-asc::after {
            border-bottom-color: currentColor;
            border-top-width: 0;
            margin-top: -3px;
        }

        #marginTable thead th.sorted-desc::after {
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
            <h2 class="section-title">Margin Management</h2>
            <p class="section-desc">Manage margin data and pricing configurations.</p>
        </div>
        <div class="d-flex align-items-center" style="gap: 0.5rem;">
            <a href="{{ route('margin.create') }}" class="btn-shadcn btn-shadcn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" x2="12" y1="5" y2="19" />
                    <line x1="5" x2="19" y1="12" y2="12" />
                </svg>
                Add Margin Data
            </a>
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
            <form method="GET" action="{{ route('margin.index') }}">
                <div class="d-flex flex-wrap align-items-center" style="gap: 0.75rem;">
                    <div class="flex-grow-1">
                        <input type="text" name="search" class="form-control-shadcn"
                            placeholder="Search by Code, Description, Margin, ARCIM_ServMateria..."
                            value="{{ request('search') }}" style="width: 100%;">
                    </div>
                    <button type="submit" class="btn-shadcn btn-shadcn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8" />
                            <path d="m21 21-4.3-4.3" />
                        </svg>
                        Search
                    </button>
                    @if(request('search'))
                        <a href="{{ route('margin.index') }}" class="btn-shadcn btn-shadcn-outline">
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
                <h3 class="card-shadcn-title mb-0">Margin Data</h3>
                <span class="badge-shadcn badge-shadcn-secondary">{{ $margins->total() }} records</span>
            </div>
            <div class="d-flex align-items-center" style="gap: 0.5rem;">
                <input type="text" id="customSearch" class="form-control-shadcn" placeholder="Quick filter..."
                    style="width: 200px; height: 32px;">
            </div>
        </div>
        <div class="card-shadcn-body" style="padding: 0;">
            <div class="table-container-shadcn" style="border: none; border-radius: 0;">
                <table id="marginTable" class="table-shadcn" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>TypeofItem Code</th>
                            <th>TypeofItem Description</th>
                            <th>Margin (%)</th>
                            <th>Date From</th>
                            <th>Date To</th>
                            <th>Status</th>
                            <th>ARCIM_ServMateria</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($margins as $index => $margin)
                            <tr>
                                <td>{{ $margins->firstItem() + $index }}</td>
                                <td><code style="font-size: 0.8125rem;">{{ $margin->TypeofItemCode ?? '-' }}</code></td>
                                <td>{{ Str::limit($margin->TypeofItemDesc, 40) ?? '-' }}</td>
                                <td>
                                    @if($margin->Margin !== null)
                                        <span class="badge-shadcn badge-shadcn-success">{{ number_format($margin->Margin) }}%</span>
                                    @else
                                        <span style="color: var(--muted-foreground);">-</span>
                                    @endif
                                </td>
                                <td>{{ $margin->DateFrom ? $margin->DateFrom->format('d/m/Y') : '-' }}</td>
                                <td>{{ $margin->DateTo ? $margin->DateTo->format('d/m/Y') : '-' }}</td>
                                <td>
                                    <span
                                        class="badge-shadcn {{ $margin->Status == 'Active' ? 'badge-shadcn-success' : 'badge-shadcn-secondary' }}">
                                        {{ $margin->Status }}
                                    </span>
                                </td>
                                <td>{{ $margin->ARCIM_ServMateria ?? '-' }}</td>
                                <td>
                                    <div class="d-flex align-items-center" style="gap: 0.375rem;">
                                        <a href="{{ route('margin.edit', $margin->id) }}"
                                            class="btn-shadcn btn-shadcn-outline btn-shadcn-sm" title="Edit">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z" />
                                                <path d="m15 5 4 4" />
                                            </svg>
                                            Edit
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center" style="padding: 3rem;">
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
        @if($margins->hasPages())
            <div class="card-shadcn-footer">
                <div class="d-flex align-items-center justify-content-between">
                    <div style="font-size: 0.875rem; color: var(--muted-foreground);">
                        Showing {{ $margins->firstItem() }}-{{ $margins->lastItem() }} of {{ $margins->total() }} records
                    </div>
                    <div>
                        {{ $margins->withQueryString()->links() }}
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

                $('#marginTable tbody tr').each(function () {
                    // Skip empty state row (with colspan)
                    if ($(this).find('td[colspan]').length > 0) {
                        return;
                    }

                    var text = $(this).text().toLowerCase();
                    $(this).toggle(text.indexOf(value) > -1);
                });

                // Show/hide "no results" message
                var visibleRows = $('#marginTable tbody tr:visible').not(':has(td[colspan])').length;
                if (visibleRows === 0 && value !== '') {
                    if ($('#noFilterResults').length === 0) {
                        $('#marginTable tbody').append(`
                            <tr id="noFilterResults">
                                <td colspan="9" class="text-center" style="padding: 2rem;">
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
            $('#marginTable thead th').on('click', function () {
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
                $('#marginTable thead th').removeClass('sorted-asc sorted-desc');
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