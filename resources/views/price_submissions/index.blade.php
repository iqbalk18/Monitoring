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
                            <th>Item Code</th>
                            <th>Description</th>
                            <th>Total Items</th>
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
                                <td>{{ $submission->ITP_ARCIM_Code }}</td>
                                <td>{{ Str::limit($submission->ITP_ARCIM_Desc, 30) }}</td>
                                <td>
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