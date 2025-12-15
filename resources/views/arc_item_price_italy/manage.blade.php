@extends('layouts.auth')
@section('title', 'Manage Item Price')

@section('body')
<nav class="navbar navbar-expand-lg">
    <div class="container-fluid px-4">
        <a class="navbar-brand d-flex align-items-center" href="{{ route('dashboard') }}">
            <img src="{{ asset('images/bih_logo.png') }}" alt="BIH Logo">
            <span>Bali International Hospital</span>
        </a>

        <div class="d-flex align-items-center ms-auto">
            <div class="me-3 text-end">
                <span class="fw-semibold text-dark">Manage Price</span><br>
                <small class="text-muted">{{ $item->ARCIM_Code }} - {{ $item->ARCIM_Desc }}</small>
            </div>
            <a href="{{ route('arc-itm-mast.index') }}" class="btn btn-outline-primary btn-sm px-3">Kembali</a>
        </div>
    </div>
</nav>

<div class="container mt-4 pb-5">
    {{-- Alert Messages --}}
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

    {{-- Header --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <h4 class="fw-bold text-primary mb-0">💰 Manage Item Price</h4>
            <p class="text-muted small mb-0">{{ $item->ARCIM_Code }} - {{ $item->ARCIM_Desc }}</p>
        </div>
    </div>

    {{-- Form Create/Edit --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-header bg-primary text-white p-4">
            <h5 class="mb-0" id="formTitle">➕ Tambah Price Baru</h5>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('arc-item-price-italy.store-manage', $item->ARCIM_Code) }}" method="POST" id="priceForm">
                @csrf
                <div id="methodField"></div>
                <input type="hidden" name="price_id" id="price_id" value="">
                <input type="hidden" name="status" value="{{ request('status') }}">
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">ITP_ARCIM_Desc</label>
                        <input type="text" 
                               name="ITP_ARCIM_Desc" 
                               class="form-control @error('ITP_ARCIM_Desc') is-invalid @enderror" 
                               value="{{ old('ITP_ARCIM_Desc', $item->ARCIM_Desc) }}"
                               placeholder="Masukkan Description" readonly>
                        @error('ITP_ARCIM_Desc')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-semibold">ITP_DateFrom <span class="text-danger">*</span></label>
                        <input type="date" 
                               name="ITP_DateFrom" 
                               class="form-control @error('ITP_DateFrom') is-invalid @enderror" 
                               value="{{ old('ITP_DateFrom') }}"
                               required>
                        @error('ITP_DateFrom')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-semibold">ITP_DateTo <span class="text-danger">*</span></label>
                        <input type="date" 
                               name="ITP_DateTo" 
                               class="form-control @error('ITP_DateTo') is-invalid @enderror" 
                               value="{{ old('ITP_DateTo') }}"
                               >
                        @error('ITP_DateTo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">ITP_TAR_Code</label>
                        <input type="text" 
                               name="ITP_TAR_Code" 
                               class="form-control @error('ITP_TAR_Code') is-invalid @enderror" 
                               value="REG"
                               placeholder="Masukkan TAR Code" readonly>
                        @error('ITP_TAR_Code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">ITP_TAR_Desc</label>
                        <input type="text" 
                               name="ITP_TAR_Desc" 
                               class="form-control @error('ITP_TAR_Desc') is-invalid @enderror" 
                               value="Standar"
                               placeholder="Masukkan TAR Description" readonly>
                        @error('ITP_TAR_Desc')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">ITP_Price <span class="text-danger">*</span></label>
                        <input type="number" 
                               step="0.01" 
                               name="ITP_Price" 
                               class="form-control @error('ITP_Price') is-invalid @enderror" 
                               value="{{ old('ITP_Price') }}"
                               placeholder="Masukkan Price"
                               min="0"
                               required>
                        @error('ITP_Price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-semibold">ITP_CTCUR_Code</label>
                        <input type="text" 
                               name="ITP_CTCUR_Code" 
                               class="form-control @error('ITP_CTCUR_Code') is-invalid @enderror" 
                               value="IDR"
                               placeholder="Currency Code" readonly>
                        @error('ITP_CTCUR_Code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-semibold">ITP_CTCUR_Desc</label>
                        <input type="text" 
                               name="ITP_CTCUR_Desc" 
                               class="form-control @error('ITP_CTCUR_Desc') is-invalid @enderror" 
                               value="Indonesian Rupiah"
                               placeholder="Currency Description" readonly>
                        @error('ITP_CTCUR_Desc')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">ITP_ROOMT_Code</label>
                        <input type="text" 
                               name="ITP_ROOMT_Code" 
                               class="form-control @error('ITP_ROOMT_Code') is-invalid @enderror" 
                               value="{{ old('ITP_ROOMT_Code') }}"
                               placeholder="" disabled>
                        @error('ITP_ROOMT_Code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">ITP_ROOMT_Desc</label>
                        <input type="text" 
                               name="ITP_ROOMT_Desc" 
                               class="form-control @error('ITP_ROOMT_Desc') is-invalid @enderror" 
                               value="{{ old('ITP_ROOMT_Desc') }}"
                               placeholder="" disabled>
                        @error('ITP_ROOMT_Desc')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">ITP_HOSP_Code</label>
                        <input type="text" 
                               name="ITP_HOSP_Code" 
                               class="form-control @error('ITP_HOSP_Code') is-invalid @enderror" 
                               value="BI00"
                               placeholder="Masukkan Hospital Code" readonly>
                        @error('ITP_HOSP_Code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">ITP_HOSP_Desc</label>
                        <input type="text" 
                               name="ITP_HOSP_Desc" 
                               class="form-control @error('ITP_HOSP_Desc') is-invalid @enderror" 
                               value="Bali International Hospital"
                               placeholder="Masukkan Hospital Description" readonly>
                        @error('ITP_HOSP_Desc')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">ITP_Rank</label>
                        <input type="text" 
                               name="ITP_Rank" 
                               class="form-control @error('ITP_Rank') is-invalid @enderror" 
                               value="99"
                               placeholder="Masukkan Rank" readonly>
                        @error('ITP_Rank')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">ITP_EpisodeType</label>
                        <input type="text" 
                               name="ITP_EpisodeType" 
                               class="form-control @error('ITP_EpisodeType') is-invalid @enderror" 
                               value="O"
                               placeholder="Masukkan Episode Type" readonly>
                        @error('ITP_EpisodeType')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-secondary px-4" onclick="resetForm()">
                        ❌ Batal
                    </button>
                    <button type="submit" class="btn btn-primary px-4" id="submitBtn">
                        💾 Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Filter Status --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <form method="GET" action="{{ route('arc-item-price-italy.manage', $item->ARCIM_Code) }}">
                <div class="row g-3 align-items-end">
                    <div class="col-md-10">
                        <label class="form-label fw-semibold small mb-2">Filter Status</label>
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="non_active" {{ request('status') == 'non_active' ? 'selected' : '' }}>Non Active</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">🔍 Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Data Table --}}
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-primary">
                        <tr>
                            <th class="px-4 py-3">No</th>
                            <th class="py-3">Date From</th>
                            <th class="py-3">Date To</th>
                            <th class="py-3">TAR Code</th>
                            <th class="py-3">TAR Desc</th>
                            <th class="py-3">Price</th>
                            <th class="py-3">Currency</th>
                            <th class="py-3">Room Type</th>
                            <th class="py-3">Hospital</th>
                            <th class="py-3">Rank</th>
                            <th class="py-3">Episode Type</th>
                            <th class="py-3">Status</th>
                            <th class="py-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($prices as $index => $price)
                        <tr>
                            <td class="px-4 py-3">{{ $prices->firstItem() + $index }}</td>
                            <td class="py-3">{{ $price->ITP_DateFrom ? $price->ITP_DateFrom->format('d/m/Y') : '-' }}</td>
                            <td class="py-3">{{ $price->ITP_DateTo ? $price->ITP_DateTo->format('d/m/Y') : '-' }}</td>
                            <td class="py-3">{{ $price->ITP_TAR_Code ?? '-' }}</td>
                            <td class="py-3">{{ Str::limit($price->ITP_TAR_Desc ?? '-', 30) }}</td>
                            <td class="py-3">
                                <span class="badge bg-success">
                                    {{ number_format($price->ITP_Price) }}
                                </span>
                            </td>
                            <td class="py-3">{{ $price->ITP_CTCUR_Code ?? '-' }}</td>
                            <td class="py-3">{{ Str::limit($price->ITP_ROOMT_Desc ?? '-', 20) }}</td>
                            <td class="py-3">{{ Str::limit($price->ITP_HOSP_Desc ?? '-', 20) }}</td>
                            <td class="py-3">{{ $price->ITP_Rank ?? '-' }}</td>
                            <td class="py-3">{{ $price->ITP_EpisodeType ?? '-' }}</td>
                            <td class="py-3">
                                @php
                                    $today = now()->startOfDay();
                                    $isActive = is_null($price->ITP_DateTo) || $price->ITP_DateTo >= $today;
                                @endphp
                                @if($isActive)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Non Active</span>
                                @endif
                            </td>
                            <td class="py-3 text-center">
                                <button type="button" 
                                        class="btn btn-warning btn-sm px-3"
                                        onclick="editPrice({{ $price->id }})"
                                        title="Edit">
                                    ✏️ Edit
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="13" class="text-center py-5 text-muted">
                                <div class="fs-1 mb-2">📭</div>
                                <p class="mb-0">Tidak ada data price yang ditemukan</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        @if($prices->hasPages())
        <div class="card-footer">
            {{ $prices->links() }}
        </div>
        @endif
    </div>
</div>

<footer>
    © {{ date('Y') }} <span>Bali International Hospital</span> — Developed by IT Department
</footer>

<script>
// Function to format date to YYYY-MM-DD for input type="date"
function formatDateForInput(dateString) {
    if (!dateString) return '';
    
    // If already in YYYY-MM-DD format, return as is
    if (/^\d{4}-\d{2}-\d{2}$/.test(dateString)) {
        return dateString;
    }
    
    // Try to parse the date
    const date = new Date(dateString);
    if (isNaN(date.getTime())) return '';
    
    // Format to YYYY-MM-DD
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    
    return `${year}-${month}-${day}`;
}

function editPrice(id) {
    fetch(`/api/arc-item-price-italy/${id}`)
        .then(response => response.json())
        .then(data => {
            console.log('Data received:', data); // Debug
            
            // Update form title
            document.getElementById('formTitle').textContent = '✏️ Edit Price';
            document.getElementById('submitBtn').textContent = '💾 Update Data';
            
            // Set form action to update
            const form = document.getElementById('priceForm');
            const arcimCode = '{{ $item->ARCIM_Code }}';
            form.action = `/arc-item-price-italy/manage/${arcimCode}/${id}`;
            
            // Add method override
            const methodField = document.getElementById('methodField');
            methodField.innerHTML = '<input type="hidden" name="_method" value="PUT">';
            
            // Fill form fields
            document.getElementById('price_id').value = data.id;
            document.querySelector('input[name="ITP_ARCIM_Desc"]').value = data.ITP_ARCIM_Desc || '';
            
            // Format dates properly for input type="date"
            document.querySelector('input[name="ITP_DateFrom"]').value = formatDateForInput(data.ITP_DateFrom);
            document.querySelector('input[name="ITP_DateTo"]').value = formatDateForInput(data.ITP_DateTo);
            
            document.querySelector('input[name="ITP_TAR_Code"]').value = data.ITP_TAR_Code || '';
            document.querySelector('input[name="ITP_TAR_Desc"]').value = data.ITP_TAR_Desc || '';
            document.querySelector('input[name="ITP_Price"]').value = data.ITP_Price || '';
            document.querySelector('input[name="ITP_CTCUR_Code"]').value = data.ITP_CTCUR_Code || '';
            document.querySelector('input[name="ITP_CTCUR_Desc"]').value = data.ITP_CTCUR_Desc || '';
            document.querySelector('input[name="ITP_ROOMT_Code"]').value = data.ITP_ROOMT_Code || '';
            document.querySelector('input[name="ITP_ROOMT_Desc"]').value = data.ITP_ROOMT_Desc || '';
            document.querySelector('input[name="ITP_HOSP_Code"]').value = data.ITP_HOSP_Code || '';
            document.querySelector('input[name="ITP_HOSP_Desc"]').value = data.ITP_HOSP_Desc || '';
            document.querySelector('input[name="ITP_Rank"]').value = data.ITP_Rank || '';
            document.querySelector('input[name="ITP_EpisodeType"]').value = data.ITP_EpisodeType || '';
            
            console.log('DateFrom formatted:', formatDateForInput(data.ITP_DateFrom)); // Debug
            console.log('DateTo formatted:', formatDateForInput(data.ITP_DateTo)); // Debug
            
            // Scroll to form
            document.getElementById('priceForm').scrollIntoView({ behavior: 'smooth', block: 'start' });
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error loading data');
        });
}

function resetForm() {
    document.getElementById('formTitle').textContent = '➕ Tambah Price Baru';
    document.getElementById('submitBtn').textContent = '💾 Simpan Data';
    const arcimCode = '{{ $item->ARCIM_Code }}';
    document.getElementById('priceForm').action = `/arc-item-price-italy/manage/${arcimCode}`;
    document.getElementById('priceForm').reset();
    document.getElementById('price_id').value = '';
    
    // Clear method field (remove PUT method)
    document.getElementById('methodField').innerHTML = '';
    
    // Set default ITP_ARCIM_Desc
    document.querySelector('input[name="ITP_ARCIM_Desc"]').value = '{{ $item->ARCIM_Desc }}';
}
</script>

<style>
    .table th {
        font-weight: 600;
        color: #004e89;
        border-bottom: 2px solid #dee2e6;
    }

    .table tbody tr {
        transition: background-color 0.2s;
    }

    .table tbody tr:hover {
        background-color: #f8f9fa;
    }

    .btn-group-sm .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
</style>
@endsection

