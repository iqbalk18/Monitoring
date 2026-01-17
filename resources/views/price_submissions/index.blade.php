@extends('layouts.app')

@section('title', 'Price Submissions - Bali International Hospital')

@section('content')
    <div class="flex-between mb-4">
        <div>
            <h2 class="section-title">Price Submissions</h2>
            <p class="section-desc">Review and approve price changes submitted by Price Entry.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert-shadcn alert-shadcn-success mb-4" role="alert">
            <div class="alert-title">Success</div>
            <div class="alert-description">{{ session('success') }}</div>
        </div>
    @endif

    <!-- Filter Card -->
    <div class="card-shadcn mb-4">
        <div class="card-shadcn-body">
            <form action="{{ route('price-submissions.index') }}" method="GET">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label-shadcn" for="search">Search</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="text-muted">
                                    <circle cx="11" cy="11" r="8" />
                                    <path d="m21 21-4.3-4.3" />
                                </svg>
                            </span>
                            <input type="text" class="form-control-shadcn border-start-0 ps-0" id="search" name="search"
                                placeholder="Search by Batch ID, Item Code, Description, or User..."
                                value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label-shadcn" for="date_filter">Date Submitted</label>
                        <input type="date" class="form-control-shadcn" id="date_filter" name="date_filter"
                            value="{{ request('date_filter') }}">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn-shadcn btn-shadcn-primary w-100">
                            Apply Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card-shadcn">
        <div class="card-shadcn-header">
            <h3 class="card-shadcn-title">Pending Approvals</h3>
        </div>
        <div class="card-shadcn-body p-0">
            <div class="table-container-shadcn shadow-none border-0 radius-0">
                <table class="table-shadcn w-100">
                    <thead>
                        <tr>
                            <th>Date Submitted</th>
                            <th>Batch ID</th>
                            <th>Type</th>
                            <th>Item Code</th>
                            <th>Type Item</th>
                            <th>Description</th>
                            <th class="text-center">Total Items</th>
                            <th>Submitted By</th>
                            <th>Approved By</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($submissions as $submission)
                            <tr>
                                <td>{{ $submission->created_at->format('d M Y H:i') }}</td>
                                <td><code style="font-size: 0.8125rem;">{{ $submission->batch_id }}</code></td>
                                <td>
                                    @if($submission->submission_type == 'ADD')
                                        <span class="badge-shadcn badge-shadcn-success">ADD</span>
                                    @elseif($submission->submission_type == 'EDIT')
                                        <span class="badge-shadcn badge-shadcn-warning">EDIT</span>
                                    @else
                                        <span class="badge-shadcn badge-shadcn-secondary">{{ $submission->submission_type ?? 'ADD' }}</span>
                                    @endif
                                </td>
                                <td>{{ $submission->ITP_ARCIM_Code }}</td>
                                <td><code class="fw-bold">{{ $submission->TypeofItemCode ?? '-' }}</code></td>
                                <td>{{ Str::limit($submission->ITP_ARCIM_Desc, 30) }}</td>
                                <td class="text-center">
                                    <span class="badge-shadcn badge-shadcn-secondary">{{ $submission->total_items }}
                                        Prices</span>
                                </td>
                                <td>{{ $submission->submitter->username ?? 'Unknown' }}</td>
                                <td>{{ $submission->approver->username ?? '-' }}</td>
                                <td>
                                    @php
                                        // Note: Status is grouped, so we rely on the status of the first/base item which should match the group
                                        $status = \App\Models\PriceSubmission::where('ITP_ARCIM_Code', $submission->ITP_ARCIM_Code)
                                            ->where('submitted_by', $submission->submitted_by)
                                            ->where('created_at', $submission->created_at)
                                            ->value('status');
                                     @endphp

                                    @if($status == 'PENDING')
                                        <span class="badge-shadcn badge-shadcn-secondary">Pending</span>
                                    @elseif($status == 'APPROVED')
                                        <span class="badge-shadcn badge-shadcn-success">Approved</span>
                                    @elseif($status == 'REJECTED')
                                        <span class="badge-shadcn badge-shadcn-destructive">Rejected</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('price-submissions.show', $submission->id) }}"
                                        class="btn-shadcn btn-shadcn-sm btn-shadcn-primary">
                                        {{ session('user')['role'] == 'PRICE_APPROVER' && $status == 'PENDING' ? 'Review Batch' : 'View Batch' }}
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">No pending submissions found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($submissions->hasPages())
            <div class="card-shadcn-footer">
                {{ $submissions->links() }}
            </div>
        @endif
    </div>
@endsection