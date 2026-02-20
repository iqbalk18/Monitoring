@extends('layouts.app')

@section('title', 'Manage Item Price - Bali International Hospital')

@push('styles')
    <style>
        .mode-card {
            border: 2px solid var(--border);
            border-radius: var(--radius);
            padding: 1rem;
            cursor: pointer;
            transition: all 0.2s;
        }

        .mode-card:hover {
            border-color: var(--primary);
            background-color: var(--accent);
        }

        .mode-card.active {
            border-color: var(--primary);
            background-color: var(--accent);
        }
    </style>
@endpush

@section('content')
    <!-- Page Header -->
    <div class="flex-between mb-4" style="flex-wrap: wrap; gap: 1rem;">
        <div>
            <h2 class="section-title">Manage Item Price</h2>
            <p class="section-desc"><code style="font-size: 0.875rem;">{{ $item->ARCIM_Code }}</code> -
                {{ $item->ARCIM_Desc }}</p>
        </div>
        <div class="d-flex align-items-center" style="gap: 0.5rem;">
            <a href="{{ route('arc-itm-mast.index') }}" class="btn-shadcn btn-shadcn-outline">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m12 19-7-7 7-7" />
                    <path d="M19 12H5" />
                </svg>
                Back
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

    <!-- Form Card -->
    <div class="card-shadcn mb-4">
        <div class="card-shadcn-header">
            <h3 class="card-shadcn-title" id="formTitle">Add New Price</h3>
            <p class="card-shadcn-description">Generate multiple prices or add single price manually.</p>
        </div>
        <div class="card-shadcn-body">
            <form action="{{ route('arc-item-price-italy.store-manage', $item->ARCIM_Code) }}" method="POST" id="priceForm">
                @csrf
                <div id="methodField"></div>
                <input type="hidden" name="price_id" id="price_id" value="">
                <input type="hidden" name="status" value="{{ request('status') }}">

                <!-- Mode Selection -->
                <div class="mb-4" id="modeSelectionContainer" @if($item->ARCIM_ServMaterial == 'M') style="display: none;"
                @endif>
                    <label class="form-label-shadcn mb-3">Select Input Mode <span class="text-danger">*</span></label>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="modeGenerate" class="mode-card active">
                                <div class="d-flex align-items-start">
                                    <input class="form-check-input-shadcn mt-1" type="radio" name="input_mode"
                                        id="modeGenerate" value="generate" checked>
                                    <div class="ms-3">
                                        <div class="fw-semibold mb-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                style="vertical-align: middle;">
                                                <path d="M3 3v18h18" />
                                                <path d="m19 9-5 5-4-4-3 3" />
                                            </svg>
                                            Generate Data (Multiple Records)
                                        </div>
                                        <small class="text-muted">Generate prices for all episode types based on margin
                                            configuration</small>
                                    </div>
                                </div>
                            </label>
                        </div>
                        <div class="col-md-6">
                            <label for="modeManual" class="mode-card active w-100">
                                <div class="d-flex align-items-start">
                                    <input class="form-check-input-shadcn mt-1" type="radio" name="input_mode"
                                        id="modeManual" value="manual">
                                    <div class="ms-3">
                                        <div class="fw-semibold mb-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                style="vertical-align: middle;">
                                                <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z" />
                                                <path d="m15 5 4 4" />
                                            </svg>
                                            Manual Input (Single Record)
                                        </div>
                                        <small class="text-muted">Add price manually for a specific episode type</small>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="separator-shadcn"></div>

                <!-- Form Fields -->
                <div class="row" style="row-gap: 1.5rem;">
                    <div class="col-md-6">
                        <div class="form-group-shadcn">
                            <label class="form-label-shadcn" for="ITP_ARCIM_Desc">Item Description</label>
                            <input type="text" id="ITP_ARCIM_Desc" name="ITP_ARCIM_Desc"
                                class="form-control-shadcn @error('ITP_ARCIM_Desc') is-invalid @enderror"
                                value="{{ old('ITP_ARCIM_Desc', $item->ARCIM_Desc) }}" readonly>
                            @error('ITP_ARCIM_Desc')
                                <div class="invalid-feedback-shadcn">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group-shadcn">
                            <label class="form-label-shadcn" for="ITP_DateFrom">
                                Date From <span class="text-danger">*</span>
                            </label>
                            <input type="date" id="ITP_DateFrom" name="ITP_DateFrom"
                                class="form-control-shadcn @error('ITP_DateFrom') is-invalid @enderror"
                                style="display: block !important;" value="{{ old('ITP_DateFrom') }}" required>
                            @error('ITP_DateFrom')
                                <div class="invalid-feedback-shadcn">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group-shadcn">
                            <label class="form-label-shadcn" for="ITP_DateTo">Date To</label>
                            <input type="date" id="ITP_DateTo" name="ITP_DateTo"
                                class="form-control-shadcn @error('ITP_DateTo') is-invalid @enderror"
                                style="display: block !important;" value="{{ old('ITP_DateTo') }}">
                            @error('ITP_DateTo')
                                <div class="invalid-feedback-shadcn">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group-shadcn">
                            <label class="form-label-shadcn" for="ITP_TAR_Code">TAR Code</label>
                            <input type="text" id="ITP_TAR_Code" name="ITP_TAR_Code" class="form-control-shadcn" value="REG"
                                readonly>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group-shadcn">
                            <label class="form-label-shadcn" for="ITP_TAR_Desc">TAR Description</label>
                            <input type="text" id="ITP_TAR_Desc" name="ITP_TAR_Desc" class="form-control-shadcn"
                                value="Standar" readonly>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group-shadcn">
                            <label class="form-label-shadcn" for="ITP_Price" id="priceLabel">
                                <span id="priceLabelText">Price</span> <span class="text-danger">*</span>
                            </label>
                            <input type="number" id="ITP_Price" step="0.01" name="ITP_Price"
                                class="form-control-shadcn @error('ITP_Price') is-invalid @enderror @error('hna') is-invalid @enderror"
                                value="{{ old('ITP_Price') }}" placeholder="Enter price" min="0" required>
                            <input type="hidden" id="hnaField" name="hna" value="{{ old('hna') }}">
                            @error('ITP_Price')
                                <div class="invalid-feedback-shadcn">{{ $message }}</div>
                            @enderror
                            @error('hna')
                                <div class="invalid-feedback-shadcn">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group-shadcn">
                            <label class="form-label-shadcn" for="ITP_CTCUR_Code">Currency Code</label>
                            <input type="text" id="ITP_CTCUR_Code" name="ITP_CTCUR_Code" class="form-control-shadcn"
                                value="IDR" readonly>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group-shadcn">
                            <label class="form-label-shadcn" for="ITP_CTCUR_Desc">Currency Description</label>
                            <input type="text" id="ITP_CTCUR_Desc" name="ITP_CTCUR_Desc" class="form-control-shadcn"
                                value="Indonesian Rupiah" readonly>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group-shadcn">
                            <label class="form-label-shadcn" for="ITP_ROOMT_Code">Room Type Code</label>
                            <input type="text" id="ITP_ROOMT_Code" name="ITP_ROOMT_Code" class="form-control-shadcn"
                                value="{{ old('ITP_ROOMT_Code') }}" disabled>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group-shadcn">
                            <label class="form-label-shadcn" for="ITP_ROOMT_Desc">Room Type Description</label>
                            <input type="text" id="ITP_ROOMT_Desc" name="ITP_ROOMT_Desc" class="form-control-shadcn"
                                value="{{ old('ITP_ROOMT_Desc') }}" disabled>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group-shadcn">
                            <label class="form-label-shadcn" for="ITP_HOSP_Code">Hospital Code</label>
                            <input type="text" id="ITP_HOSP_Code" name="ITP_HOSP_Code" class="form-control-shadcn"
                                value="BI00" readonly>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group-shadcn">
                            <label class="form-label-shadcn" for="ITP_HOSP_Desc">Hospital Description</label>
                            <input type="text" id="ITP_HOSP_Desc" name="ITP_HOSP_Desc" class="form-control-shadcn"
                                value="Bali International Hospital" readonly>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group-shadcn">
                            <label class="form-label-shadcn" for="ITP_Rank">Rank</label>
                            <input type="text" id="ITP_Rank" name="ITP_Rank" class="form-control-shadcn" value="99"
                                readonly>
                        </div>
                    </div>

                    <div class="col-md-6" id="episodeTypeContainer" @if($item->ARCIM_ServMaterial == 'M')
                    style="display: none;" @endif>
                        <div class="form-group-shadcn">
                            <label class="form-label-shadcn" for="ITP_EpisodeType">
                                Episode Type <span class="text-danger manual-required" style="display:none;">*</span>
                            </label>
                            <select name="ITP_EpisodeType" id="ITP_EpisodeType"
                                class="form-select-shadcn @error('ITP_EpisodeType') is-invalid @enderror">
                                <option value="">-- Select Episode Type --</option>
                                <option value="O" selected>O - Outpatient</option>
                                <option value="E">E - Emergency</option>
                                <option value="VIP">I - Single Rooms</option>
                                <option value="VVIP">I - Serenity</option>
                                <option value="SUITE">I - President</option>
                                <option value="CU">I - Critical Care</option>
                            </select>
                            @error('ITP_EpisodeType')
                                <div class="invalid-feedback-shadcn">{{ $message }}</div>
                            @enderror
                            <p class="form-description-shadcn" id="episodeTypeHelp">Pilih episode type sebagai harga awal (initial price). Harga episode lainnya dihitung otomatis dari margin.</p>
                        </div>
                    </div>

                    <div class="col-md-6" id="episodeTypeMaterialContainer" @if($item->ARCIM_ServMaterial != 'M')
                    style="display: none;" @endif>
                        <div class="form-group-shadcn">
                            <label class="form-label-shadcn" for="ITP_EpisodeType_Material">
                                Episode Type <span class="text-danger">*</span>
                            </label>
                            <select name="ITP_EpisodeType" id="ITP_EpisodeType_Material"
                                class="form-select-shadcn @error('ITP_EpisodeType') is-invalid @enderror"
                                @if($item->ARCIM_ServMaterial != 'M') disabled @endif>
                                <option value="">-- Select Episode Type --</option>
                                <option value="O" {{ old('ITP_EpisodeType') == 'O' ? 'selected' : '' }}>O - Outpatient</option>
                                <option value="E" {{ old('ITP_EpisodeType') == 'E' ? 'selected' : '' }}>E - Emergency</option>
                                <option value="VIP" {{ old('ITP_EpisodeType') == 'VIP' ? 'selected' : '' }}>I - Single Rooms</option>
                                <option value="VVIP" {{ old('ITP_EpisodeType') == 'VVIP' ? 'selected' : '' }}>I - Serenity</option>
                                <option value="SUITE" {{ old('ITP_EpisodeType') == 'SUITE' ? 'selected' : '' }}>I - President</option>
                                <option value="CU" {{ old('ITP_EpisodeType') == 'CU' ? 'selected' : '' }}>I - Critical Care</option>
                            </select>
                            @error('ITP_EpisodeType')
                                <div class="invalid-feedback-shadcn">{{ $message }}</div>
                            @enderror
                            <p class="form-description-shadcn">Select episode type for Material</p>
                        </div>
                    </div>

                    <div class="col-md-6" id="typeOfItemContainer" @if($item->ARCIM_ServMaterial != 'M')
                    style="display: none;" @endif>
                        <div class="form-group-shadcn">
                            <label class="form-label-shadcn" for="TypeofItemCode">
                                Type of Item Code <span class="text-danger">*</span>
                            </label>
                            @if($item->ARCIM_ServMaterial == 'M')
                                @php
                                    $typeOfItemCode = $item->TypeofItemCode ?? '';
                                    $typeOfItemDesc = $item->TypeofItemDesc ?? '';
                                    $displayValue = $typeOfItemCode;
                                    if ($typeOfItemDesc) {
                                        $displayValue .= ' - ' . $typeOfItemDesc;
                                    }
                                @endphp
                                <input type="text" id="TypeofItemCode" name="TypeofItemCode"
                                    class="form-control-shadcn @error('TypeofItemCode') is-invalid @enderror"
                                    value="{{ old('TypeofItemCode', $typeOfItemCode) }}" readonly required>
                                @if($typeOfItemDesc)
                                    <input type="hidden" name="TypeofItemDesc" value="{{ $typeOfItemDesc }}">
                                    <p class="form-description-shadcn mt-1"
                                        style="font-size: 0.875rem; color: var(--muted-foreground);">
                                        {{ $displayValue }}
                                    </p>
                                @endif
                            @else
                                <select name="TypeofItemCode" id="TypeofItemCode"
                                    class="form-select-shadcn @error('TypeofItemCode') is-invalid @enderror">
                                    <option value="">-- Select Type of Item --</option>
                                    @if(isset($materialMargins) && $materialMargins->count() > 0)
                                        @foreach($materialMargins as $margin)
                                            <option value="{{ $margin->TypeofItemCode }}" {{ old('TypeofItemCode') == $margin->TypeofItemCode ? 'selected' : '' }}>
                                                {{ $margin->TypeofItemCode }} - {{ $margin->TypeofItemDesc ?? '' }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            @endif
                            @error('TypeofItemCode')
                                <div class="invalid-feedback-shadcn">{{ $message }}</div>
                            @enderror
                            @if($item->ARCIM_ServMaterial == 'M')
                                <p class="form-description-shadcn">Type of item code is read-only and retrieved from database
                                </p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="separator-shadcn"></div>

                <!-- Action Buttons -->
                <div class="d-flex justify-content-end align-items-center mt-4" style="gap: 0.75rem;">
                    <button type="button" class="btn-shadcn btn-shadcn-outline" onclick="resetForm()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" x2="6" y1="6" y2="18" />
                            <line x1="6" x2="18" y1="6" y2="18" />
                        </svg>
                        Cancel
                    </button>
                    <button type="submit" class="btn-shadcn btn-shadcn-success" id="manualBtn" name="action" value="manual"
                        @if($item->ARCIM_ServMaterial != 'M') style="display:none;" @endif>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                            <polyline points="17 21 17 13 7 13 7 21" />
                            <polyline points="7 3 7 8 15 8" />
                        </svg>
                        @if($item->ARCIM_ServMaterial == 'M')
                            Save HNA Data
                        @else
                            Save Manual Data
                        @endif
                    </button>
                    <button type="submit" class="btn-shadcn btn-shadcn-primary" id="generateBtn" name="action"
                        value="generate" @if($item->ARCIM_ServMaterial == 'M') style="display: none;" @endif>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 3v18h18" />
                            <path d="m19 9-5 5-4-4-3 3" />
                        </svg>
                        Generate Data
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card-shadcn mb-4">
        <div class="card-shadcn-body">
            <form method="GET" action="{{ route('arc-item-price-italy.manage', $item->ARCIM_Code) }}">
                <div class="d-flex align-items-end justify-content-end" style="gap: 0.75rem;">
                    <div>
                        <label class="form-label-shadcn" for="statusFilter">Filter by Status</label>
                        <select name="status" id="statusFilter" class="form-select-shadcn" style="width: 200px;">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="non_active" {{ request('status') == 'non_active' ? 'selected' : '' }}>Non Active
                            </option>
                        </select>
                    </div>
                    <button type="submit" class="btn-shadcn btn-shadcn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 3H2l8 9.46V19l4 2v-8.54L22 3z" />
                        </svg>
                        Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Data Table -->
    <div class="card-shadcn">
        <div class="card-shadcn-header flex-between">
            <div class="d-flex align-items-center" style="gap: 0.75rem;">
                <h3 class="card-shadcn-title mb-0">Price List</h3>
                <span class="badge-shadcn badge-shadcn-secondary">{{ $prices->total() }} records</span>
            </div>
        </div>
        <div class="card-shadcn-body" style="padding: 0;">
            <div class="table-container-shadcn" style="border: none; border-radius: 0;">
                <table class="table-shadcn" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Batch ID</th>
                            <th>Date From</th>
                            <th>Date To</th>
                            <th>TAR Code</th>
                            <th>TAR Desc</th>
                            <th>Price</th>
                            <th>Currency</th>
                            <th>Room Type</th>
                            <th>Hospital</th>
                            <th>Rank</th>
                            <th>Episode Type</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($prices as $index => $price)
                            <tr>
                                <td>{{ $prices->firstItem() + $index }}</td>
                                <td><code style="font-size: 0.8125rem;">{{ $price->batch_id }}</code></td>
                                <td>{{ $price->ITP_DateFrom ? $price->ITP_DateFrom->format('d/m/Y') : '-' }}</td>
                                <td>{{ $price->ITP_DateTo ? $price->ITP_DateTo->format('d/m/Y') : '-' }}</td>
                                <td><code style="font-size: 0.8125rem;">{{ $price->ITP_TAR_Code ?? '-' }}</code></td>
                                <td>{{ Str::limit($price->ITP_TAR_Desc ?? '-', 30) }}</td>
                                <td>
                                    <span class="badge-shadcn badge-shadcn-success">
                                        {{ number_format($price->ITP_Price) }}
                                    </span>
                                </td>
                                <td>{{ $price->ITP_CTCUR_Code ?? '-' }}</td>
                                <td>{{ Str::limit($price->ITP_ROOMT_Desc ?? '-', 20) }}</td>
                                <td>{{ Str::limit($price->ITP_HOSP_Desc ?? '-', 20) }}</td>
                                <td>{{ $price->ITP_Rank ?? '-' }}</td>
                                <td><code style="font-size: 0.8125rem;">{{ $price->ITP_EpisodeType ?? '-' }}</code></td>
                                <td>
                                    @php
                                        $today = now()->startOfDay();
                                        $isActive = is_null($price->ITP_DateTo) || $price->ITP_DateTo >= $today;
                                    @endphp
                                    @if($isActive)
                                        <span class="badge-shadcn badge-shadcn-success">Active</span>
                                    @else
                                        <span class="badge-shadcn badge-shadcn-secondary">Non Active</span>
                                    @endif
                                </td>
                                <td>
                                    <button type="button" class="btn-shadcn btn-shadcn-outline btn-shadcn-sm"
                                        onclick="editPrice({{ $price->id }})" title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z" />
                                            <path d="m15 5 4 4" />
                                        </svg>
                                        Edit
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="13" class="text-center" style="padding: 3rem;">
                                    <div style="color: var(--muted-foreground);">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round"
                                            stroke-linejoin="round" style="opacity: 0.5; margin-bottom: 1rem;">
                                            <circle cx="12" cy="12" r="10" />
                                            <path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8" />
                                            <path d="M12 18V6" />
                                        </svg>
                                        <p class="mb-0" style="font-size: 0.875rem;">No price data found</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($prices->hasPages())
            <div class="card-shadcn-footer">
                <div class="d-flex align-items-center justify-content-between">
                    <div style="font-size: 0.875rem; color: var(--muted-foreground);">
                        Showing {{ $prices->firstItem() }}-{{ $prices->lastItem() }} of {{ $prices->total() }} records
                    </div>
                    <div>
                        {{ $prices->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        // Function to format date to YYYY-MM-DD for input type="date"
        function formatDateForInput(dateString) {
            if (!dateString) return '';

            if (/^\d{4}-\d{2}-\d{2}$/.test(dateString)) {
                return dateString;
            }

            const date = new Date(dateString);
            if (isNaN(date.getTime())) return '';

            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');

            return `${year}-${month}-${day}`;
        }

        function editPrice(id) {
            fetch(`/api/arc-item-price-italy/${id}`)
                .then(response => response.json())
                .then(data => {
                    console.log('Data received:', data);

                    document.getElementById('formTitle').textContent = 'Edit Price';
                    document.querySelector('.card-shadcn-description').textContent = 'Update existing price data.';

                    // Hide mode selection when editing
                    document.getElementById('modeSelectionContainer').style.display = 'none';

                    // Hide all action buttons except update
                    document.getElementById('manualBtn').style.display = 'none';
                    document.getElementById('generateBtn').innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    Update Data
                `;
                    document.getElementById('generateBtn').style.display = 'inline-block';

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

                    // Handle Material type - set hna value
                    const servMaterial = '{{ $item->ARCIM_ServMaterial }}';
                    const priceInput = document.getElementById('ITP_Price');
                    const hnaField = document.getElementById('hnaField');
                    const typeOfItemField = document.getElementById('TypeofItemCode');

                    if (servMaterial === 'M') {
                        // For Material, use hna value
                        if (data.hna !== null && data.hna !== undefined) {
                            priceInput.value = data.hna;
                            hnaField.value = data.hna;
                        } else {
                            priceInput.value = '';
                            hnaField.value = '';
                        }

                        // Show Type of Item Code field (readonly, value from item master database)
                        const typeOfItemContainer = document.getElementById('typeOfItemContainer');
                        if (typeOfItemContainer) {
                            typeOfItemContainer.style.display = 'block';
                        }
                        // Type of Item Code is readonly and comes from item master (arc_itm_mast) table
                        // Value is already set in the view from $item->TypeofItemCode

                        // Show Material-specific Episode Type field and set value
                        const episodeTypeMaterialContainer = document.getElementById('episodeTypeMaterialContainer');
                        if (episodeTypeMaterialContainer) {
                            episodeTypeMaterialContainer.style.display = 'block';
                        }
                        const episodeTypeMaterialField = document.getElementById('ITP_EpisodeType_Material');
                        if (episodeTypeMaterialField) {
                            episodeTypeMaterialField.value = data.ITP_EpisodeType || '';
                        }
                    } else {
                        // For Service, use ITP_Price
                        priceInput.value = data.ITP_Price || '';
                    }

                    document.querySelector('input[name="ITP_CTCUR_Code"]').value = data.ITP_CTCUR_Code || '';
                    document.querySelector('input[name="ITP_CTCUR_Desc"]').value = data.ITP_CTCUR_Desc || '';
                    document.querySelector('input[name="ITP_ROOMT_Code"]').value = data.ITP_ROOMT_Code || '';
                    document.querySelector('input[name="ITP_ROOMT_Desc"]').value = data.ITP_ROOMT_Desc || '';
                    document.querySelector('input[name="ITP_HOSP_Code"]').value = data.ITP_HOSP_Code || '';
                    document.querySelector('input[name="ITP_HOSP_Desc"]').value = data.ITP_HOSP_Desc || '';
                    document.querySelector('input[name="ITP_Rank"]').value = data.ITP_Rank || '';

                    // Set episode type value for select element
                    if (servMaterial === 'M') {
                        // For Material, use the Material-specific Episode Type field
                        const episodeTypeMaterialSelect = document.getElementById('ITP_EpisodeType_Material');
                        if (episodeTypeMaterialSelect) {
                            episodeTypeMaterialSelect.value = data.ITP_EpisodeType || '';
                        }
                    } else {
                        // For Service, use the standard Episode Type field
                        const episodeTypeSelect = document.getElementById('ITP_EpisodeType');
                        if (episodeTypeSelect) {
                            episodeTypeSelect.value = data.ITP_EpisodeType || '';
                        }
                    }

                    // Scroll to form
                    document.getElementById('priceForm').scrollIntoView({ behavior: 'smooth', block: 'start' });
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error loading data');
                });
        }

        function resetForm() {
            document.getElementById('formTitle').textContent = 'Add New Price';
            document.querySelector('.card-shadcn-description').textContent = 'Generate multiple prices or add single price manually.';
            const arcimCode = '{{ $item->ARCIM_Code }}';
            document.getElementById('priceForm').action = `/arc-item-price-italy/manage/${arcimCode}`;
            document.getElementById('priceForm').reset();
            document.getElementById('price_id').value = '';

            // Clear method field (remove PUT method)
            document.getElementById('methodField').innerHTML = '';

            // Set default ITP_ARCIM_Desc
            document.querySelector('input[name="ITP_ARCIM_Desc"]').value = '{{ $item->ARCIM_Desc }}';

            // Show mode selection
            document.getElementById('modeSelectionContainer').style.display = 'block';

            // Reset to Generate mode
            document.getElementById('modeGenerate').checked = true;
            document.querySelectorAll('.mode-card').forEach(card => card.classList.remove('active'));
            document.querySelector('label[for="modeGenerate"]').classList.add('active');

            // Reset episode type
            const servMaterial = '{{ $item->ARCIM_ServMaterial }}';
            if (servMaterial !== 'M') {
                document.getElementById('ITP_EpisodeType').disabled = false;
                document.getElementById('ITP_EpisodeType').required = true;
                document.getElementById('ITP_EpisodeType').value = 'O';
                document.getElementById('episodeTypeHelp').style.display = 'block';
                document.getElementById('episodeTypeHelp').textContent = 'Pilih episode type sebagai harga awal (initial price). Harga episode lainnya dihitung otomatis dari margin.';
                document.querySelector('.manual-required').style.display = 'inline';
            }

            // Reset Type of Item Code for Material
            if (servMaterial === 'M') {
                const typeOfItemField = document.getElementById('TypeofItemCode');
                if (typeOfItemField) {
                    typeOfItemField.value = '';
                }
            }

            // Reset buttons
            if (servMaterial === 'M') {
                document.getElementById('manualBtn').style.display = 'inline-block';
                document.getElementById('generateBtn').style.display = 'none';
            } else {
                document.getElementById('manualBtn').style.display = 'none';
                document.getElementById('generateBtn').innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><path d="m19 9-5 5-4-4-3 3"/></svg>
                Generate Data
            `;
                document.getElementById('generateBtn').style.display = 'inline-block';
            }

            // Reset Batch ID display
            const batchIdContainer = document.getElementById('batchIdContainer');
            if (batchIdContainer) {
                batchIdContainer.style.display = 'none';
                document.getElementById('batch_id_display').value = '';
            }
        }

        // Handle mode selection (Manual or Generate)
        document.addEventListener('DOMContentLoaded', function () {
            const servMaterial = '{{ $item->ARCIM_ServMaterial }}';
            const modeManual = document.getElementById('modeManual');
            const modeGenerate = document.getElementById('modeGenerate');
            const episodeTypeField = document.getElementById('ITP_EpisodeType');
            const episodeTypeHelp = document.getElementById('episodeTypeHelp');
            const manualRequired = document.querySelector('.manual-required');
            const manualBtn = document.getElementById('manualBtn');
            const generateBtn = document.getElementById('generateBtn');
            const priceInput = document.getElementById('ITP_Price');
            const hnaField = document.getElementById('hnaField');
            const priceLabelText = document.getElementById('priceLabelText');

            // Initialize form based on serv/material type
            const typeOfItemContainer = document.getElementById('typeOfItemContainer');
            const typeOfItemField = document.getElementById('TypeofItemCode');

            if (servMaterial === 'M') {
                // Material mode: Change label to HNA, disable episode type, hide generate, show Type of Item
                priceLabelText.textContent = 'HNA';
                // Keep the input name as ITP_Price for display, but we'll handle it in form submit
                // Hide standard Episode Type field for Material
                if (episodeTypeField) {
                    episodeTypeField.disabled = true;
                    episodeTypeField.required = false;
                    episodeTypeField.value = '';
                    episodeTypeField.name = 'ITP_EpisodeType';
                }
                if (episodeTypeHelp) {
                    episodeTypeHelp.style.display = 'none';
                }
                manualRequired.style.display = 'none';
                manualBtn.style.display = 'inline-block';
                generateBtn.style.display = 'none';

                // Show Material-specific Episode Type field
                const episodeTypeMaterialContainer = document.getElementById('episodeTypeMaterialContainer');
                if (episodeTypeMaterialContainer) {
                    episodeTypeMaterialContainer.style.display = 'block';
                }

                // Show Type of Item Code field for Material (readonly, value from database)
                if (typeOfItemContainer) {
                    typeOfItemContainer.style.display = 'block';
                }
                if (typeOfItemField) {
                    // Field is readonly, so we don't need to set required
                    // Value is already set from database in the view
                }

                // Show manual button as default for Material
                if (modeManual) {
                    modeManual.checked = true;
                }
            } else {
                // Service mode: Standard behavior
                priceLabelText.textContent = 'Price';
                priceInput.name = 'ITP_Price';
                priceInput.id = 'ITP_Price';

                // Hide Type of Item Code field for Service
                if (typeOfItemContainer) {
                    typeOfItemContainer.style.display = 'none';
                }
                if (typeOfItemField) {
                    typeOfItemField.required = false;
                }

                // Hide Material-specific Episode Type field for Service
                const episodeTypeMaterialContainerService = document.getElementById('episodeTypeMaterialContainer');
                if (episodeTypeMaterialContainerService) {
                    episodeTypeMaterialContainerService.style.display = 'none';
                }

                // Enable episode type select for Service (selectable as initial price)
                if (episodeTypeField) {
                    episodeTypeField.disabled = false;
                    episodeTypeField.value = episodeTypeField.value || 'O';
                }
            }

            // Function to toggle mode (only for Service)
            function toggleMode() {
                if (servMaterial === 'M') {
                    return; // Don't toggle for Material
                }

                // Update card styles
                document.querySelectorAll('.mode-card').forEach(card => card.classList.remove('active'));

                if (modeManual.checked) {
                    document.querySelector('label[for="modeManual"]').classList.add('active');

                    // Manual mode: Enable dropdown, make required, show manual button
                    episodeTypeField.disabled = false;
                    episodeTypeField.required = true;
                    episodeTypeField.value = ''; // Clear selection to force user choice
                    episodeTypeHelp.style.display = 'block';
                    episodeTypeHelp.textContent = 'Select episode type for manual input';
                    manualRequired.style.display = 'inline';
                    manualBtn.style.display = 'inline-block';
                    generateBtn.style.display = 'none';
                } else {
                    document.querySelector('label[for="modeGenerate"]').classList.add('active');

                    // Generate mode: Enable dropdown (selectable as initial/base price), set default O
                    episodeTypeField.disabled = false;
                    episodeTypeField.required = true;
                    if (!episodeTypeField.value) episodeTypeField.value = 'O';
                    episodeTypeHelp.style.display = 'block';
                    episodeTypeHelp.textContent = 'Pilih episode type sebagai harga awal (initial price). Harga episode lainnya dihitung otomatis dari margin.';
                    manualRequired.style.display = 'inline';
                    manualBtn.style.display = 'none';
                    generateBtn.style.display = 'inline-block';
                }
            }

            // Add event listeners to radio buttons (only for Service)
            if (servMaterial !== 'M') {
                if (modeManual) {
                    modeManual.addEventListener('change', toggleMode);
                }
                if (modeGenerate) {
                    modeGenerate.addEventListener('change', toggleMode);
                }
                // Initialize on page load
                toggleMode();
            }

            // Handle form submission for Material - copy value to hna field
            if (servMaterial === 'M') {
                const form = document.getElementById('priceForm');
                form.addEventListener('submit', function (e) {
                    // Copy value from price input to hna hidden field
                    hnaField.value = priceInput.value;
                    // Change input name to hna for submission
                    priceInput.name = 'hna';

                    // Handle Episode Type for Material - get value from Material-specific field
                    const episodeTypeMaterialField = document.getElementById('ITP_EpisodeType_Material');
                    if (episodeTypeMaterialField) {
                        // Create or update hidden field with Episode Type value
                        let episodeTypeHidden = document.querySelector('input[name="ITP_EpisodeType"][type="hidden"]');
                        if (!episodeTypeHidden) {
                            episodeTypeHidden = document.createElement('input');
                            episodeTypeHidden.type = 'hidden';
                            episodeTypeHidden.name = 'ITP_EpisodeType';
                            form.appendChild(episodeTypeHidden);
                        }
                        // If empty string, set to null (will be converted in controller)
                        episodeTypeHidden.value = episodeTypeMaterialField.value || '';
                    }

                    // Ensure Type of Item Code is submitted
                    if (typeOfItemField && !typeOfItemField.value) {
                        e.preventDefault();
                        alert('Type of Item Code wajib dipilih');
                        return false;
                    }
                });
            }
        });
    </script>
@endpush