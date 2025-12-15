@extends('layouts.auth')
@section('title', 'Edit Data ARC Item Master')

@section('body')
<nav class="navbar navbar-expand-lg">
    <div class="container-fluid px-4">
        <a class="navbar-brand d-flex align-items-center" href="{{ route('dashboard') }}">
            <img src="{{ asset('images/bih_logo.png') }}" alt="BIH Logo">
            <span>Bali International Hospital</span>
        </a>

        <div class="d-flex align-items-center ms-auto">
            <div class="me-3 text-end">
                <span class="fw-semibold text-dark">Edit Data</span><br>
                <small class="text-muted">ARC Item Master</small>
            </div>
            <a href="{{ route('arc-itm-mast.index') }}" class="btn btn-outline-primary btn-sm px-3">Kembali</a>
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
                    <h4 class="mb-0 fw-bold">✏️ Edit Data</h4>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('arc-itm-mast.update', $item->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            {{-- Item Information --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Item Code (ARCIM_Code)</label>
                                <input type="text" 
                                       name="ARCIM_Code" 
                                       class="form-control @error('ARCIM_Code') is-invalid @enderror" 
                                       value="{{ old('ARCIM_Code', $item->ARCIM_Code) }}"
                                       placeholder="Masukkan Item Code">
                                @error('ARCIM_Code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Item Description (ARCIM_Desc)</label>
                                <input type="text" 
                                       name="ARCIM_Desc" 
                                       class="form-control @error('ARCIM_Desc') is-invalid @enderror" 
                                       value="{{ old('ARCIM_Desc', $item->ARCIM_Desc) }}"
                                       placeholder="Masukkan Description">
                                @error('ARCIM_Desc')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Service Material (ARCIM_ServMaterial)</label>
                                <input type="text" 
                                       name="ARCIM_ServMaterial" 
                                       class="form-control @error('ARCIM_ServMaterial') is-invalid @enderror" 
                                       value="{{ old('ARCIM_ServMaterial', $item->ARCIM_ServMaterial) }}"
                                       placeholder="Masukkan Service Material">
                                @error('ARCIM_ServMaterial')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Category Information --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Item Category Code (ARCIC_Code)</label>
                                <input type="text" 
                                       name="ARCIC_Code" 
                                       class="form-control @error('ARCIC_Code') is-invalid @enderror" 
                                       value="{{ old('ARCIC_Code', $item->ARCIC_Code) }}"
                                       placeholder="Masukkan Category Code">
                                @error('ARCIC_Code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Item Category Desc (ARCIC_Desc)</label>
                                <input type="text" 
                                       name="ARCIC_Desc" 
                                       class="form-control @error('ARCIC_Desc') is-invalid @enderror" 
                                       value="{{ old('ARCIC_Desc', $item->ARCIC_Desc) }}"
                                       placeholder="Masukkan Category Description">
                                @error('ARCIC_Desc')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Order Category Code (ORCAT_Code)</label>
                                <input type="text" 
                                       name="ORCAT_Code" 
                                       class="form-control @error('ORCAT_Code') is-invalid @enderror" 
                                       value="{{ old('ORCAT_Code', $item->ORCAT_Code) }}"
                                       placeholder="Masukkan Order Category Code">
                                @error('ORCAT_Code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Order Category Desc (ORCAT_Desc)</label>
                                <input type="text" 
                                       name="ORCAT_Desc" 
                                       class="form-control @error('ORCAT_Desc') is-invalid @enderror" 
                                       value="{{ old('ORCAT_Desc', $item->ORCAT_Desc) }}"
                                       placeholder="Masukkan Order Category Description">
                                @error('ORCAT_Desc')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Segment Information --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Segment Code (ARCSG_Code)</label>
                                <input type="text" 
                                       name="ARCSG_Code" 
                                       class="form-control @error('ARCSG_Code') is-invalid @enderror" 
                                       value="{{ old('ARCSG_Code', $item->ARCSG_Code) }}"
                                       placeholder="Masukkan Segment Code">
                                @error('ARCSG_Code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Segment Description (ARCSG_Desc)</label>
                                <input type="text" 
                                       name="ARCSG_Desc" 
                                       class="form-control @error('ARCSG_Desc') is-invalid @enderror" 
                                       value="{{ old('ARCSG_Desc', $item->ARCSG_Desc) }}"
                                       placeholder="Masukkan Segment Description">
                                @error('ARCSG_Desc')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Business Group Information --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Business Group Code (ARCBG_Code)</label>
                                <input type="text" 
                                       name="ARCBG_Code" 
                                       class="form-control @error('ARCBG_Code') is-invalid @enderror" 
                                       value="{{ old('ARCBG_Code', $item->ARCBG_Code) }}"
                                       placeholder="Masukkan Business Group Code">
                                @error('ARCBG_Code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Business Group Desc (ARCBG_Desc)</label>
                                <input type="text" 
                                       name="ARCBG_Desc" 
                                       class="form-control @error('ARCBG_Desc') is-invalid @enderror" 
                                       value="{{ old('ARCBG_Desc', $item->ARCBG_Desc) }}"
                                       placeholder="Masukkan Business Group Description">
                                @error('ARCBG_Desc')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Order Settings --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Order On Its Own (ARCIM_OrderOnItsOwn)</label>
                                <div class="form-check">
                                    <input type="checkbox" 
                                           class="form-check-input @error('ARCIM_OrderOnItsOwn') is-invalid @enderror" 
                                           id="ARCIM_OrderOnItsOwn"
                                           {{ old('ARCIM_OrderOnItsOwn', $item->ARCIM_OrderOnItsOwn) == 'Y' ? 'checked' : '' }}
                                           onchange="handleOrderOnItsOwnChange()">
                                    <input type="hidden" 
                                           name="ARCIM_OrderOnItsOwn" 
                                           id="ARCIM_OrderOnItsOwn_hidden"
                                           value="{{ old('ARCIM_OrderOnItsOwn', $item->ARCIM_OrderOnItsOwn) == 'Y' ? 'Y' : 'N' }}">
                                    <label class="form-check-label" for="ARCIM_OrderOnItsOwn">
                                        Order On Its Own
                                    </label>
                                </div>
                                @error('ARCIM_OrderOnItsOwn')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3" id="reorderContainer" @if(old('ARCIM_OrderOnItsOwn', $item->ARCIM_OrderOnItsOwn) != 'Y') style="display: none;" @endif>
                                <label class="form-label fw-semibold">Reorder On Its Own (ARCIM_ReorderOnItsOwn)</label>
                                <div class="form-check">
                                    <input type="checkbox" 
                                           class="form-check-input @error('ARCIM_ReorderOnItsOwn') is-invalid @enderror" 
                                           id="ARCIM_ReorderOnItsOwn"
                                           {{ old('ARCIM_ReorderOnItsOwn', $item->ARCIM_ReorderOnItsOwn) == 'Y' ? 'checked' : '' }}
                                           onchange="handleReorderOnItsOwnChange()">
                                    <input type="hidden" 
                                           name="ARCIM_ReorderOnItsOwn" 
                                           id="ARCIM_ReorderOnItsOwn_hidden"
                                           value="{{ old('ARCIM_ReorderOnItsOwn', $item->ARCIM_ReorderOnItsOwn) == 'Y' ? 'Y' : 'N' }}">
                                    <label class="form-check-label" for="ARCIM_ReorderOnItsOwn">
                                        Reorder On Its Own
                                    </label>
                                </div>
                                @error('ARCIM_ReorderOnItsOwn')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Effective Dates --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Effective Date From (ARCIM_EffDate)</label>
                                <input type="date" 
                                       name="ARCIM_EffDate" 
                                       class="form-control @error('ARCIM_EffDate') is-invalid @enderror" 
                                       value="{{ old('ARCIM_EffDate', $item->ARCIM_EffDate ? $item->ARCIM_EffDate->format('Y-m-d') : '') }}">
                                @error('ARCIM_EffDate')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Effective Date To (ARCIM_EffDateTo)</label>
                                <input type="date" 
                                       name="ARCIM_EffDateTo" 
                                       class="form-control @error('ARCIM_EffDateTo') is-invalid @enderror" 
                                       value="{{ old('ARCIM_EffDateTo', $item->ARCIM_EffDateTo ? $item->ARCIM_EffDateTo->format('Y-m-d') : '') }}">
                                @error('ARCIM_EffDateTo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('arc-itm-mast.index') }}" class="btn bg-danger text-white px-4">
                                ❌ Batal
                            </a>
                            <button type="submit" class="btn bg-success px-4 text-white">
                                💾 Update Data
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

<div class="modal fade" id="priceModal" tabindex="-1" aria-labelledby="priceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="priceModalLabel">💰 Manage Item Price</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <button type="button" class="btn btn-success btn-sm" onclick="showAddForm()">
                        ➕ Tambah Price
                    </button>
                </div>

                <div id="priceForm" style="display: none;" class="card mb-3">
                    <div class="card-body">
                        <h6 class="card-title" id="formTitle">Tambah Price</h6>
                        <form id="priceFormData">
                            <input type="hidden" id="priceId" name="id">
                            <input type="hidden" id="ITP_ARCIM_Code" name="ITP_ARCIM_Code" value="{{ $item->ARCIM_Code }}">
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <label class="form-label small">ITP_ARCIM_Code</label>
                                    <input type="text" class="form-control form-control-sm" id="ITP_ARCIM_Code_input" value="{{ $item->ARCIM_Code }}" readonly>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label small">ITP_ARCIM_Desc</label>
                                    <input type="text" class="form-control form-control-sm" name="ITP_ARCIM_Desc" id="ITP_ARCIM_Desc" value="{{ $item->ARCIM_Desc }}" readonly>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label small">ITP_DateFrom</label>
                                    <input type="date" class="form-control form-control-sm" name="ITP_DateFrom" id="ITP_DateFrom">
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label small">ITP_DateTo</label>
                                    <input type="date" class="form-control form-control-sm" name="ITP_DateTo" id="ITP_DateTo">
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label small">ITP_TAR_Code</label>
                                    <input type="text" class="form-control form-control-sm" name="ITP_TAR_Code" id="ITP_TAR_Code">
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label small">ITP_TAR_Desc</label>
                                    <input type="text" class="form-control form-control-sm" name="ITP_TAR_Desc" id="ITP_TAR_Desc">
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label small">ITP_Price</label>
                                    <input type="number" step="0.01" class="form-control form-control-sm" name="ITP_Price" id="ITP_Price">
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label small">ITP_CTCUR_Code</label>
                                    <input type="text" class="form-control form-control-sm" name="ITP_CTCUR_Code" id="ITP_CTCUR_Code">
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label small">ITP_CTCUR_Desc</label>
                                    <input type="text" class="form-control form-control-sm" name="ITP_CTCUR_Desc" id="ITP_CTCUR_Desc">
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label small">ITP_ROOMT_Code</label>
                                    <input type="text" class="form-control form-control-sm" name="ITP_ROOMT_Code" id="ITP_ROOMT_Code">
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label small">ITP_ROOMT_Desc</label>
                                    <input type="text" class="form-control form-control-sm" name="ITP_ROOMT_Desc" id="ITP_ROOMT_Desc">
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label small">ITP_HOSP_Code</label>
                                    <input type="text" class="form-control form-control-sm" name="ITP_HOSP_Code" id="ITP_HOSP_Code">
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label small">ITP_HOSP_Desc</label>
                                    <input type="text" class="form-control form-control-sm" name="ITP_HOSP_Desc" id="ITP_HOSP_Desc">
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label small">ITP_Rank</label>
                                    <input type="text" class="form-control form-control-sm" name="ITP_Rank" id="ITP_Rank">
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label small">ITP_EpisodeType</label>
                                    <input type="text" class="form-control form-control-sm" name="ITP_EpisodeType" id="ITP_EpisodeType">
                                </div>
                            </div>
                            <div class="mt-3">
                                <button type="button" class="btn btn-primary btn-sm" onclick="savePrice()">Simpan</button>
                                <button type="button" class="btn btn-secondary btn-sm" onclick="hideAddForm()">Batal</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-sm table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>ITP_ARCIM_Code</th>
                                <th>ITP_ARCIM_Desc</th>
                                <th>ITP_DateFrom</th>
                                <th>ITP_DateTo</th>
                                <th>ITP_TAR_Code</th>
                                <th>ITP_TAR_Desc</th>
                                <th>ITP_Price</th>
                                <th>ITP_CTCUR_Code</th>
                                <th>ITP_CTCUR_Desc</th>
                                <th>ITP_ROOMT_Code</th>
                                <th>ITP_ROOMT_Desc</th>
                                <th>ITP_HOSP_Code</th>
                                <th>ITP_HOSP_Desc</th>
                                <th>ITP_Rank</th>
                                <th>ITP_EpisodeType</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="priceTableBody">
                            <tr>
                                <td colspan="16" class="text-center">Loading...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
let currentArcimCode = '{{ $item->ARCIM_Code }}';

function loadPrices(arcimCode) {
    currentArcimCode = arcimCode;
    fetch(`/api/arc-item-price-italy?arcim_code=${arcimCode}`)
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById('priceTableBody');
            if (data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="16" class="text-center">Tidak ada data</td></tr>';
                return;
            }
            tbody.innerHTML = data.map(price => `
                <tr>
                    <td>${price.ITP_ARCIM_Code || '-'}</td>
                    <td>${price.ITP_ARCIM_Desc || '-'}</td>
                    <td>${price.ITP_DateFrom || '-'}</td>
                    <td>${price.ITP_DateTo || '-'}</td>
                    <td>${price.ITP_TAR_Code || '-'}</td>
                    <td>${price.ITP_TAR_Desc || '-'}</td>
                    <td>${price.ITP_Price || '-'}</td>
                    <td>${price.ITP_CTCUR_Code || '-'}</td>
                    <td>${price.ITP_CTCUR_Desc || '-'}</td>
                    <td>${price.ITP_ROOMT_Code || '-'}</td>
                    <td>${price.ITP_ROOMT_Desc || '-'}</td>
                    <td>${price.ITP_HOSP_Code || '-'}</td>
                    <td>${price.ITP_HOSP_Desc || '-'}</td>
                    <td>${price.ITP_Rank || '-'}</td>
                    <td>${price.ITP_EpisodeType || '-'}</td>
                    <td>
                        <button class="btn btn-sm btn-warning" onclick="editPrice(${price.id})">✏️</button>
                    </td>
                </tr>
            `).join('');
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('priceTableBody').innerHTML = '<tr><td colspan="16" class="text-center text-danger">Error loading data</td></tr>';
        });
}

function showAddForm() {
    document.getElementById('priceForm').style.display = 'block';
    document.getElementById('formTitle').textContent = 'Tambah Price';
    document.getElementById('priceFormData').reset();
    document.getElementById('priceId').value = '';
    document.getElementById('ITP_ARCIM_Code').value = currentArcimCode;
    document.getElementById('ITP_ARCIM_Code_input').value = currentArcimCode;
}

function hideAddForm() {
    document.getElementById('priceForm').style.display = 'none';
    document.getElementById('priceFormData').reset();
    document.getElementById('priceId').value = '';
}

function savePrice() {
    const form = document.getElementById('priceFormData');
    const formData = new FormData(form);
    const data = Object.fromEntries(formData);
    const priceId = document.getElementById('priceId').value;

    const url = priceId ? `/api/arc-item-price-italy/${priceId}` : '/api/arc-item-price-italy';
    const method = priceId ? 'PUT' : 'POST';

    fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(result => {
        if (result.errors) {
            alert('Error: ' + JSON.stringify(result.errors));
        } else {
            alert(result.message || 'Data berhasil disimpan');
            hideAddForm();
            loadPrices(currentArcimCode);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error menyimpan data');
    });
}

function editPrice(id) {
    fetch(`/api/arc-item-price-italy/${id}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('priceForm').style.display = 'block';
            document.getElementById('formTitle').textContent = 'Edit Price';
            document.getElementById('priceId').value = data.id;
            document.getElementById('ITP_ARCIM_Code').value = data.ITP_ARCIM_Code || '';
            document.getElementById('ITP_ARCIM_Code_input').value = data.ITP_ARCIM_Code || '';
            document.getElementById('ITP_ARCIM_Desc').value = data.ITP_ARCIM_Desc || '';
            document.getElementById('ITP_DateFrom').value = data.ITP_DateFrom || '';
            document.getElementById('ITP_DateTo').value = data.ITP_DateTo || '';
            document.getElementById('ITP_TAR_Code').value = data.ITP_TAR_Code || '';
            document.getElementById('ITP_TAR_Desc').value = data.ITP_TAR_Desc || '';
            document.getElementById('ITP_Price').value = data.ITP_Price || '';
            document.getElementById('ITP_CTCUR_Code').value = data.ITP_CTCUR_Code || '';
            document.getElementById('ITP_CTCUR_Desc').value = data.ITP_CTCUR_Desc || '';
            document.getElementById('ITP_ROOMT_Code').value = data.ITP_ROOMT_Code || '';
            document.getElementById('ITP_ROOMT_Desc').value = data.ITP_ROOMT_Desc || '';
            document.getElementById('ITP_HOSP_Code').value = data.ITP_HOSP_Code || '';
            document.getElementById('ITP_HOSP_Desc').value = data.ITP_HOSP_Desc || '';
            document.getElementById('ITP_Rank').value = data.ITP_Rank || '';
            document.getElementById('ITP_EpisodeType').value = data.ITP_EpisodeType || '';
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error loading data');
        });
}

function handleOrderOnItsOwnChange() {
    const orderCheckbox = document.getElementById('ARCIM_OrderOnItsOwn');
    const orderHidden = document.getElementById('ARCIM_OrderOnItsOwn_hidden');
    const reorderContainer = document.getElementById('reorderContainer');
    const reorderCheckbox = document.getElementById('ARCIM_ReorderOnItsOwn');
    const reorderHidden = document.getElementById('ARCIM_ReorderOnItsOwn_hidden');

    if (orderCheckbox.checked) {
        orderHidden.value = 'Y';
        reorderContainer.style.display = '';
        reorderCheckbox.checked = true;
        reorderHidden.value = 'Y';
    } else {
        orderHidden.value = 'N';
        reorderContainer.style.display = 'none';
        reorderCheckbox.checked = false;
        reorderHidden.value = 'N';
    }
}

function handleReorderOnItsOwnChange() {
    const reorderCheckbox = document.getElementById('ARCIM_ReorderOnItsOwn');
    const reorderHidden = document.getElementById('ARCIM_ReorderOnItsOwn_hidden');

    if (reorderCheckbox.checked) {
        reorderHidden.value = 'Y';
    } else {
        reorderHidden.value = 'N';
    }
}
</script>

@endsection




