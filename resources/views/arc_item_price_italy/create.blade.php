@extends('layouts.auth')
@section('title', 'Tambah Item Price Italy')

@section('body')
<nav class="navbar navbar-expand-lg">
    <div class="container-fluid px-4">
        <a class="navbar-brand d-flex align-items-center" href="{{ route('dashboard') }}">
            <img src="{{ asset('images/bih_logo.png') }}" alt="BIH Logo">
            <span>Bali International Hospital</span>
        </a>

        <div class="d-flex align-items-center ms-auto">
            <div class="me-3 text-end">
                <span class="fw-semibold text-dark">Tambah Item Price</span><br>
                <small class="text-muted">ARC Item Price Italy</small>
            </div>
            <a href="{{ route('arc-itm-mast.index') }}" class="btn btn-outline-primary btn-sm px-3">Kembali</a>
        </div>
    </div>
</nav>

<div class="container mt-4 pb-5">
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

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
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-primary text-white p-4">
                    <h4 class="mb-0 fw-bold">➕ Tambah Item Price Italy</h4>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('arc-item-price-italy.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">ITP_ARCIM_Code <span class="text-danger">*</span></label>
                                <select name="ITP_ARCIM_Code" 
                                        class="form-select @error('ITP_ARCIM_Code') is-invalid @enderror" 
                                        id="ITP_ARCIM_Code"
                                        required
                                        onchange="updateDescription()">
                                    <option value="">Pilih Item Code</option>
                                    @foreach($items as $item)
                                        <option value="{{ $item->ARCIM_Code }}" 
                                                data-desc="{{ $item->ARCIM_Desc }}"
                                                {{ (old('ITP_ARCIM_Code', $selectedArcimCode ?? '') == $item->ARCIM_Code) ? 'selected' : '' }}>
                                            {{ $item->ARCIM_Code }} - {{ $item->ARCIM_Desc }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('ITP_ARCIM_Code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">ITP_ARCIM_Desc</label>
                                <input type="text" 
                                       name="ITP_ARCIM_Desc" 
                                       class="form-control @error('ITP_ARCIM_Desc') is-invalid @enderror" 
                                       id="ITP_ARCIM_Desc"
                                       value="{{ old('ITP_ARCIM_Desc') }}"
                                       readonly>
                                @error('ITP_ARCIM_Desc')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">ITP_DateFrom</label>
                                <input type="date" 
                                       name="ITP_DateFrom" 
                                       class="form-control @error('ITP_DateFrom') is-invalid @enderror" 
                                       value="{{ old('ITP_DateFrom') }}">
                                @error('ITP_DateFrom')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">ITP_DateTo</label>
                                <input type="date" 
                                       name="ITP_DateTo" 
                                       class="form-control @error('ITP_DateTo') is-invalid @enderror" 
                                       value="{{ old('ITP_DateTo') }}">
                                @error('ITP_DateTo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">ITP_TAR_Code</label>
                                <input type="text" 
                                       name="ITP_TAR_Code" 
                                       class="form-control @error('ITP_TAR_Code') is-invalid @enderror" 
                                       value="{{ old('ITP_TAR_Code') }}">
                                @error('ITP_TAR_Code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">ITP_TAR_Desc</label>
                                <input type="text" 
                                       name="ITP_TAR_Desc" 
                                       class="form-control @error('ITP_TAR_Desc') is-invalid @enderror" 
                                       value="{{ old('ITP_TAR_Desc') }}">
                                @error('ITP_TAR_Desc')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">ITP_Price</label>
                                <input type="number" 
                                       step="0.01" 
                                       name="ITP_Price" 
                                       class="form-control @error('ITP_Price') is-invalid @enderror" 
                                       value="{{ old('ITP_Price') }}">
                                @error('ITP_Price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">ITP_CTCUR_Code</label>
                                <input type="text" 
                                       name="ITP_CTCUR_Code" 
                                       class="form-control @error('ITP_CTCUR_Code') is-invalid @enderror" 
                                       value="{{ old('ITP_CTCUR_Code') }}">
                                @error('ITP_CTCUR_Code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">ITP_CTCUR_Desc</label>
                                <input type="text" 
                                       name="ITP_CTCUR_Desc" 
                                       class="form-control @error('ITP_CTCUR_Desc') is-invalid @enderror" 
                                       value="{{ old('ITP_CTCUR_Desc') }}">
                                @error('ITP_CTCUR_Desc')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">ITP_ROOMT_Code</label>
                                <input type="text" 
                                       name="ITP_ROOMT_Code" 
                                       class="form-control @error('ITP_ROOMT_Code') is-invalid @enderror" 
                                       value="{{ old('ITP_ROOMT_Code') }}">
                                @error('ITP_ROOMT_Code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">ITP_ROOMT_Desc</label>
                                <input type="text" 
                                       name="ITP_ROOMT_Desc" 
                                       class="form-control @error('ITP_ROOMT_Desc') is-invalid @enderror" 
                                       value="{{ old('ITP_ROOMT_Desc') }}">
                                @error('ITP_ROOMT_Desc')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">ITP_HOSP_Code</label>
                                <input type="text" 
                                       name="ITP_HOSP_Code" 
                                       class="form-control @error('ITP_HOSP_Code') is-invalid @enderror" 
                                       value="{{ old('ITP_HOSP_Code') }}">
                                @error('ITP_HOSP_Code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">ITP_HOSP_Desc</label>
                                <input type="text" 
                                       name="ITP_HOSP_Desc" 
                                       class="form-control @error('ITP_HOSP_Desc') is-invalid @enderror" 
                                       value="{{ old('ITP_HOSP_Desc') }}">
                                @error('ITP_HOSP_Desc')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">ITP_Rank</label>
                                <input type="text" 
                                       name="ITP_Rank" 
                                       class="form-control @error('ITP_Rank') is-invalid @enderror" 
                                       value="{{ old('ITP_Rank') }}">
                                @error('ITP_Rank')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">ITP_EpisodeType</label>
                                <input type="text" 
                                       name="ITP_EpisodeType" 
                                       class="form-control @error('ITP_EpisodeType') is-invalid @enderror" 
                                       value="{{ old('ITP_EpisodeType') }}">
                                @error('ITP_EpisodeType')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('arc-itm-mast.index') }}" class="btn btn-secondary px-4">
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

<script>
function updateDescription() {
    const select = document.getElementById('ITP_ARCIM_Code');
    const descInput = document.getElementById('ITP_ARCIM_Desc');
    const selectedOption = select.options[select.selectedIndex];
    
    if (selectedOption.value) {
        descInput.value = selectedOption.getAttribute('data-desc') || '';
    } else {
        descInput.value = '';
    }
}

// Auto-update description on page load if item is pre-selected
document.addEventListener('DOMContentLoaded', function() {
    updateDescription();
});
</script>
@endsection

