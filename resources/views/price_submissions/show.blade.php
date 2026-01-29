@extends('layouts.app')

@section('title', 'Review Price Submission - Bali International Hospital')

@section('content')
    <div class="mb-4">
        <a href="{{ route('price-submissions.index') }}" class="btn-shadcn btn-shadcn-ghost btn-shadcn-sm mb-2">
            ← Back to List
        </a>
        <h2 class="section-title">Review Submission #{{ $baseSubmission->id }}
            @if($baseSubmission->submission_type == 'ADD')
                <span class="badge-shadcn badge-shadcn-success ms-2"
                    style="font-size: 0.8em; vertical-align: middle;">ADD</span>
            @elseif($baseSubmission->submission_type == 'EDIT')
                <span class="badge-shadcn badge-shadcn-warning ms-2"
                    style="font-size: 0.8em; vertical-align: middle;">EDIT</span>
            @elseif($baseSubmission->submission_type)
                <span class="badge-shadcn badge-shadcn-secondary ms-2"
                    style="font-size: 0.8em; vertical-align: middle;">{{ $baseSubmission->submission_type }}</span>
            @endif
        </h2>
    </div>

    @if($errors->any())
        <div class="alert-shadcn alert-shadcn-destructive mb-4">
            <div class="alert-title">Error</div>
            <ul class="alert-description mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid-layout" style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem;">
        <!-- Details -->
        <div class="card-shadcn">
            <div class="card-shadcn-header">
                <h3 class="card-shadcn-title">Item Details</h3>
            </div>
            <div class="card-shadcn-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label-shadcn">Item Code</label>
                        <p class="fw-bold">{{ $baseSubmission->ITP_ARCIM_Code }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-shadcn">Description</label>
                        <p>{{ $baseSubmission->ITP_ARCIM_Desc }}</p>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label class="form-label-shadcn">Batch ID</label>
                        <p><code class="fw-bold">{{ $baseSubmission->batch_id }}</code></p>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label-shadcn">Date From</label>
                        <p>{{ $baseSubmission->ITP_DateFrom ? \Carbon\Carbon::parse($baseSubmission->ITP_DateFrom)->format('d M Y') : '-' }}
                        </p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-shadcn">Date To</label>
                        <p>{{ $baseSubmission->ITP_DateTo ? \Carbon\Carbon::parse($baseSubmission->ITP_DateTo)->format('d M Y') : '-' }}
                        </p>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label-shadcn mb-2">Submitted Prices in Batch</label>
                    <div class="table-container-shadcn">
                        <table class="table-shadcn w-100">
                            <thead>
                                <tr>
                                    <th>Type of Item Code</th>
                                    <th>Episode Type</th>
                                    <th>Room Type</th>
                                    <th class="text-end">Submitted Price (IDR)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($submissions as $sub)
                                    <tr>
                                        <td><span style="color: black;">{{ $sub->item->TypeofItemCode ?? '-' }}</span></td>
                                        <td>{{ $sub->ITP_EpisodeType }}</td>
                                        <td>{{ $sub->ITP_ROOMT_Desc ?? '-' }}</td>
                                        <td class="text-end fw-bold">{{ number_format($sub->ITP_Price, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label-shadcn">Submitted By</label>
                        <p>{{ $baseSubmission->submitter->username ?? 'Unknown' }} <span
                                class="text-muted">({{ $baseSubmission->created_at->diffForHumans() }})</span></p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-shadcn">Approved / Rejected By</label>
                        <p>{{ $baseSubmission->approver->username ?? '-' }}</p>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <label class="form-label-shadcn">Status</label>
                        <div>
                            @if($baseSubmission->status == 'PENDING')
                                <span class="badge-shadcn badge-shadcn-secondary">Pending Approval</span>
                            @elseif($baseSubmission->status == 'APPROVED')
                                <span class="badge-shadcn badge-shadcn-success">Approved</span>
                            @elseif($baseSubmission->status == 'REJECTED')
                                <span class="badge-shadcn badge-shadcn-destructive">Rejected</span>
                            @endif
                        </div>
                    </div>
                </div>

                @if($baseSubmission->status == 'REJECTED')
                    <div class="alert-shadcn alert-shadcn-destructive mt-3">
                        <strong>Rejection Reason:</strong>
                        <p class="mb-0">{{ $baseSubmission->rejection_reason }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Actions -->
        @if($baseSubmission->status == 'PENDING' && user_has_role(session('user'), 'PRICE_APPROVER'))
            <div class="d-flex flex-column gap-3">
                <!-- Approve -->
                <div class="card-shadcn">
                    <div class="card-shadcn-body">
                        <h4 class="card-shadcn-title mb-3">Approve Batch Submission</h4>
                        <p class="text-muted small mb-3">This will sync <strong>{{ $submissions->count() }} prices</strong> to
                            TrakCare and activate them in the system.</p>
                        <form action="{{ route('price-submissions.approve', $baseSubmission->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-shadcn btn-shadcn-success w-100">
                                ✓ Approve & Sync Batch
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Reject -->
                <div class="card-shadcn">
                    <div class="card-shadcn-body">
                        <h4 class="card-shadcn-title mb-3">Reject Batch Submission</h4>
                        <form action="{{ route('price-submissions.reject', $baseSubmission->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label-shadcn">Reason for Rejection</label>
                                <textarea name="rejection_reason" class="form-control-shadcn" rows="3" required
                                    placeholder="e.g., Price too high, Incorrect date..."></textarea>
                            </div>
                            <button type="submit" class="btn-shadcn btn-shadcn-destructive w-100">
                                ✕ Reject Batch
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection