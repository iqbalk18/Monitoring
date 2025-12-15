@extends('layouts.auth')
@section('title', 'Detail Data Margin')

@section('body')
<nav class="navbar navbar-expand-lg">
    <div class="container-fluid px-4">
        <a class="navbar-brand d-flex align-items-center" href="{{ route('dashboard') }}">
            <img src="{{ asset('images/bih_logo.png') }}" alt="BIH Logo">
            <span>Bali International Hospital</span>
        </a>

        <div class="d-flex align-items-center ms-auto">
            <div class="me-3 text-end">
                <span class="fw-semibold text-dark">Detail Data</span><br>
                <small class="text-muted">Margin</small>
            </div>
            <a href="{{ route('margin.index') }}" class="btn btn-outline-primary btn-sm px-3">Kembali</a>
        </div>
    </div>
</nav>

<div class="container mt-4 pb-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-info text-white p-4">
                    <h4 class="mb-0 fw-bold">👁️ Detail Data Margin</h4>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold text-muted">TypeofItem Code</label>
                            <div class="form-control-plaintext bg-light p-2 rounded">
                                {{ $margin->TypeofItemCode ?? '-' }}
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold text-muted">TypeofItem Description</label>
                            <div class="form-control-plaintext bg-light p-2 rounded">
                                {{ $margin->TypeofItemDesc ?? '-' }}
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold text-muted">Margin (%)</label>
                            <div class="form-control-plaintext bg-light p-2 rounded">
                                @if($margin->Margin !== null)
                                    <span class="badge bg-success">{{ number_format($margin->Margin, 2) }}%</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold text-muted">ARCIM_ServMateria</label>
                            <div class="form-control-plaintext bg-light p-2 rounded">
                                {{ $margin->ARCIM_ServMateria ?? '-' }}
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold text-muted">Created At</label>
                            <div class="form-control-plaintext bg-light p-2 rounded">
                                {{ $margin->created_at ? $margin->created_at->format('d/m/Y H:i:s') : '-' }}
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold text-muted">Updated At</label>
                            <div class="form-control-plaintext bg-light p-2 rounded">
                                {{ $margin->updated_at ? $margin->updated_at->format('d/m/Y H:i:s') : '-' }}
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('margin.index') }}" class="btn btn-secondary px-4">
                            ← Kembali
                        </a>
                        <a href="{{ route('margin.edit', $margin->id) }}" class="btn btn-warning px-4">
                            ✏️ Edit Data
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<footer>
    © {{ date('Y') }} <span>Bali International Hospital</span> — Developed by IT Department
</footer>
@endsection

