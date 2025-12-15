@extends('layouts.auth')
@section('title', 'Tambah Data Margin')

@section('body')
<nav class="navbar navbar-expand-lg">
    <div class="container-fluid px-4">
        <a class="navbar-brand d-flex align-items-center" href="{{ route('dashboard') }}">
            <img src="{{ asset('images/bih_logo.png') }}" alt="BIH Logo">
            <span>Bali International Hospital</span>
        </a>

        <div class="d-flex align-items-center ms-auto">
            <div class="me-3 text-end">
                <span class="fw-semibold text-dark">Tambah Data</span><br>
                <small class="text-muted">Margin</small>
            </div>
            <a href="{{ route('margin.index') }}" class="btn btn-outline-primary btn-sm px-3">Kembali</a>
        </div>
    </div>
</nav>

<div class="container mt-4 pb-5">
    {{-- Errors --}}
    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-primary text-white p-4">
                    <h4 class="mb-0 fw-bold">➕ Tambah Data Margin</h4>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('margin.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">TypeofItem Code</label>
                                <input type="text" 
                                       name="TypeofItemCode" 
                                       class="form-control @error('TypeofItemCode') is-invalid @enderror" 
                                       value="{{ old('TypeofItemCode') }}"
                                       placeholder="Masukkan TypeofItem Code">
                                @error('TypeofItemCode')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">TypeofItem Description</label>
                                <input type="text" 
                                       name="TypeofItemDesc" 
                                       class="form-control @error('TypeofItemDesc') is-invalid @enderror" 
                                       value="{{ old('TypeofItemDesc') }}"
                                       placeholder="Masukkan TypeofItem Description">
                                @error('TypeofItemDesc')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Margin (%)</label>
                                <div class="input-group">
                                    <input type="number" 
                                           step="0.01" 
                                           min="0" 
                                           max="100"
                                           name="Margin" 
                                           class="form-control @error('Margin') is-invalid @enderror" 
                                           value="{{ old('Margin') }}"
                                           placeholder="Masukkan Margin">
                                    <span class="input-group-text">%</span>
                                </div>
                                @error('Margin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">ARCIM_ServMateria</label>
                                <input type="text" 
                                       name="ARCIM_ServMateria" 
                                       class="form-control @error('ARCIM_ServMateria') is-invalid @enderror" 
                                       value="{{ old('ARCIM_ServMateria') }}"
                                       placeholder="Masukkan ARCIM_ServMateria">
                                @error('ARCIM_ServMateria')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('margin.index') }}" class="btn btn-secondary px-4">
                                ❌ Batal
                            </a>
                            <button type="submit" class="btn btn-primary px-4">
                                💾 Simpan Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<footer>
    © {{ date('Y') }} <span>Bali International Hospital</span> — Developed by IT Department
</footer>
@endsection

