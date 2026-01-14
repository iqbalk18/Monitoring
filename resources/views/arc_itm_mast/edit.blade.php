@extends('layouts.app')

@section('title', 'Edit Data ARC Item Master - Bali International Hospital')

@section('content')
<!-- Page Header -->
<div class="flex-between mb-4" style="flex-wrap: wrap; gap: 1rem;">
    <div>
        <h2 class="section-title">Edit ARC Item Master</h2>
        <p class="section-desc">Update existing item master data and pricing configuration.</p>
    </div>
    <div class="d-flex align-items-center" style="gap: 0.5rem;">
        <a href="{{ route('arc-itm-mast.index') }}" class="btn-shadcn btn-shadcn-outline">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
            Kembali
        </a>
    </div>
</div>

<!-- Alerts -->
<div id="alertContainer">
    @if($errors->any())
    <div class="alert-shadcn alert-shadcn-destructive" role="alert">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" x2="9" y1="9" y2="15"/><line x1="9" x2="15" y1="9" y2="15"/></svg>
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
<div class="card-shadcn">
    <div class="card-shadcn-header">
        <div class="d-flex align-items-center" style="gap: 0.5rem;">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
            <h3 class="card-shadcn-title mb-0">Item Information</h3>
        </div>
        <p class="card-shadcn-description">Update the item master details below.</p>
    </div>
    <div class="card-shadcn-body">
        <form action="{{ route('arc-itm-mast.update', $item->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row" style="row-gap: 1.5rem;">
                {{-- Basic Item Information --}}
                <div class="col-12">
                    <h6 class="form-section-title-shadcn">Basic Information</h6>
                </div>

                <div class="col-md-6">
                    <div class="form-group-shadcn">
                        <label class="form-label-shadcn" for="ARCIM_Code">
                            Item Code (ARCIM_Code)
                            <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               id="ARCIM_Code"
                               name="ARCIM_Code" 
                               class="form-control-shadcn @error('ARCIM_Code') is-invalid @enderror" 
                               value="{{ old('ARCIM_Code', $item->ARCIM_Code) }}"
                               placeholder="Enter Item Code"
                               required>
                        @error('ARCIM_Code')
                            <div class="invalid-feedback-shadcn">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group-shadcn">
                        <label class="form-label-shadcn" for="ARCIM_Desc">
                            Item Description (ARCIM_Desc)
                            <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               id="ARCIM_Desc"
                               name="ARCIM_Desc" 
                               class="form-control-shadcn @error('ARCIM_Desc') is-invalid @enderror" 
                               value="{{ old('ARCIM_Desc', $item->ARCIM_Desc) }}"
                               placeholder="Enter Description"
                               required>
                        @error('ARCIM_Desc')
                            <div class="invalid-feedback-shadcn">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group-shadcn">
                        <label class="form-label-shadcn" for="ARCIM_ServMaterial">
                            Service Material (ARCIM_ServMaterial)
                        </label>
                        <select id="ARCIM_ServMaterial"
                                name="ARCIM_ServMaterial" 
                                class="form-select-shadcn @error('ARCIM_ServMaterial') is-invalid @enderror">
                            <option value="">-- Select Type --</option>
                            <option value="S" {{ old('ARCIM_ServMaterial', $item->ARCIM_ServMaterial) == 'S' ? 'selected' : '' }}>S - Service</option>
                            <option value="M" {{ old('ARCIM_ServMaterial', $item->ARCIM_ServMaterial) == 'M' ? 'selected' : '' }}>M - Material</option>
                        </select>
                        @error('ARCIM_ServMaterial')
                            <div class="invalid-feedback-shadcn">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group-shadcn">
                        <label class="form-label-shadcn" for="TypeofItemCode">
                            Type of Item Code
                        </label>
                        @if($item->ARCIM_ServMaterial == 'M' && isset($materialMargins) && $materialMargins->count() > 0)
                            {{-- Dropdown for Material (M) --}}
                            <select name="TypeofItemCode" 
                                    id="TypeofItemCode"
                                    class="form-select-shadcn @error('TypeofItemCode') is-invalid @enderror">
                                <option value="">-- Select Type of Item --</option>
                                @foreach($materialMargins as $margin)
                                    <option value="{{ $margin->TypeofItemCode }}" {{ old('TypeofItemCode', $item->TypeofItemCode) == $margin->TypeofItemCode ? 'selected' : '' }}>
                                        {{ $margin->TypeofItemCode }} - {{ $margin->TypeofItemDesc ?? '' }}
                                    </option>
                                @endforeach
                            </select>
                        @else
                            {{-- Text input for Service (S) or if no margins available --}}
                            <input type="text"
                                   id="TypeofItemCode"
                                   name="TypeofItemCode"
                                   class="form-control-shadcn @error('TypeofItemCode') is-invalid @enderror"
                                   value="{{ old('TypeofItemCode', $item->TypeofItemCode) }}"
                                   placeholder="Enter Type of Item Code">
                        @endif
                        @error('TypeofItemCode')
                            <div class="invalid-feedback-shadcn">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group-shadcn">
                        <label class="form-label-shadcn" for="TypeofItemDesc">
                            Type of Item Desc
                        </label>
                        <input type="text"
                               id="TypeofItemDesc"
                               name="TypeofItemDesc"
                               class="form-control-shadcn @error('TypeofItemDesc') is-invalid @enderror"
                               value="{{ old('TypeofItemDesc', $item->TypeofItemDesc) }}"
                               placeholder="Enter Type of Item Desc">
                        @error('TypeofItemDesc')
                            <div class="invalid-feedback-shadcn">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Category Information --}}
                <div class="col-12 mt-3">
                    <div class="separator-shadcn"></div>
                    <h6 class="form-section-title-shadcn">Category Information</h6>
                </div>

                <div class="col-md-6">
                    <div class="form-group-shadcn">
                        <label class="form-label-shadcn" for="ARCIC_Code">Item Category Code (ARCIC_Code)</label>
                        <input type="text" 
                               id="ARCIC_Code"
                               name="ARCIC_Code" 
                               class="form-control-shadcn @error('ARCIC_Code') is-invalid @enderror" 
                               value="{{ old('ARCIC_Code', $item->ARCIC_Code) }}"
                               placeholder="Enter Category Code">
                        @error('ARCIC_Code')
                            <div class="invalid-feedback-shadcn">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group-shadcn">
                        <label class="form-label-shadcn" for="ARCIC_Desc">Item Category Desc (ARCIC_Desc)</label>
                        <input type="text" 
                               id="ARCIC_Desc"
                               name="ARCIC_Desc" 
                               class="form-control-shadcn @error('ARCIC_Desc') is-invalid @enderror" 
                               value="{{ old('ARCIC_Desc', $item->ARCIC_Desc) }}"
                               placeholder="Enter Category Description">
                        @error('ARCIC_Desc')
                            <div class="invalid-feedback-shadcn">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group-shadcn">
                        <label class="form-label-shadcn" for="ORCAT_Code">Order Category Code (ORCAT_Code)</label>
                        <input type="text" 
                               id="ORCAT_Code"
                               name="ORCAT_Code" 
                               class="form-control-shadcn @error('ORCAT_Code') is-invalid @enderror" 
                               value="{{ old('ORCAT_Code', $item->ORCAT_Code) }}"
                               placeholder="Enter Order Category Code">
                        @error('ORCAT_Code')
                            <div class="invalid-feedback-shadcn">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group-shadcn">
                        <label class="form-label-shadcn" for="ORCAT_Desc">Order Category Desc (ORCAT_Desc)</label>
                        <input type="text" 
                               id="ORCAT_Desc"
                               name="ORCAT_Desc" 
                               class="form-control-shadcn @error('ORCAT_Desc') is-invalid @enderror" 
                               value="{{ old('ORCAT_Desc', $item->ORCAT_Desc) }}"
                               placeholder="Enter Order Category Description">
                        @error('ORCAT_Desc')
                            <div class="invalid-feedback-shadcn">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Segment Information --}}
                <div class="col-12 mt-3">
                    <div class="separator-shadcn"></div>
                    <h6 class="form-section-title-shadcn">Segment Information</h6>
                </div>

                <div class="col-md-6">
                    <div class="form-group-shadcn">
                        <label class="form-label-shadcn" for="ARCSG_Code">Segment Code (ARCSG_Code)</label>
                        <input type="text" 
                               id="ARCSG_Code"
                               name="ARCSG_Code" 
                               class="form-control-shadcn @error('ARCSG_Code') is-invalid @enderror" 
                               value="{{ old('ARCSG_Code', $item->ARCSG_Code) }}"
                               placeholder="Enter Segment Code">
                        @error('ARCSG_Code')
                            <div class="invalid-feedback-shadcn">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group-shadcn">
                        <label class="form-label-shadcn" for="ARCSG_Desc">Segment Description (ARCSG_Desc)</label>
                        <input type="text" 
                               id="ARCSG_Desc"
                               name="ARCSG_Desc" 
                               class="form-control-shadcn @error('ARCSG_Desc') is-invalid @enderror" 
                               value="{{ old('ARCSG_Desc', $item->ARCSG_Desc) }}"
                               placeholder="Enter Segment Description">
                        @error('ARCSG_Desc')
                            <div class="invalid-feedback-shadcn">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Business Group Information --}}
                <div class="col-12 mt-3">
                    <div class="separator-shadcn"></div>
                    <h6 class="form-section-title-shadcn">Business Group Information</h6>
                </div>

                <div class="col-md-6">
                    <div class="form-group-shadcn">
                        <label class="form-label-shadcn" for="ARCBG_Code">Business Group Code (ARCBG_Code)</label>
                        <input type="text" 
                               id="ARCBG_Code"
                               name="ARCBG_Code" 
                               class="form-control-shadcn @error('ARCBG_Code') is-invalid @enderror" 
                               value="{{ old('ARCBG_Code', $item->ARCBG_Code) }}"
                               placeholder="Enter Business Group Code">
                        @error('ARCBG_Code')
                            <div class="invalid-feedback-shadcn">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group-shadcn">
                        <label class="form-label-shadcn" for="ARCBG_Desc">Business Group Desc (ARCBG_Desc)</label>
                        <input type="text" 
                               id="ARCBG_Desc"
                               name="ARCBG_Desc" 
                               class="form-control-shadcn @error('ARCBG_Desc') is-invalid @enderror" 
                               value="{{ old('ARCBG_Desc', $item->ARCBG_Desc) }}"
                               placeholder="Enter Business Group Description">
                        @error('ARCBG_Desc')
                            <div class="invalid-feedback-shadcn">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Order Settings --}}
                <div class="col-12 mt-3">
                    <div class="separator-shadcn"></div>
                    <h6 class="form-section-title-shadcn">Order Settings</h6>
                </div>

                <div class="col-md-6">
                    <div class="form-group-shadcn">
                        <label class="form-label-shadcn">Order On Its Own (ARCIM_OrderOnItsOwn)</label>
                        <div class="d-flex align-items-center" style="gap: 0.5rem;">
                            <input type="checkbox" 
                                   class="form-check-input-shadcn @error('ARCIM_OrderOnItsOwn') is-invalid @enderror" 
                                   id="ARCIM_OrderOnItsOwn"
                                   {{ old('ARCIM_OrderOnItsOwn', $item->ARCIM_OrderOnItsOwn) == 'Y' ? 'checked' : '' }}
                                   onchange="handleOrderOnItsOwnChange()">
                            <input type="hidden" 
                                   name="ARCIM_OrderOnItsOwn" 
                                   id="ARCIM_OrderOnItsOwn_hidden"
                                   value="{{ old('ARCIM_OrderOnItsOwn', $item->ARCIM_OrderOnItsOwn) == 'Y' ? 'Y' : 'N' }}">
                            <label class="form-check-label-shadcn" for="ARCIM_OrderOnItsOwn">
                                Enable Order On Its Own
                            </label>
                        </div>
                        @error('ARCIM_OrderOnItsOwn')
                            <div class="invalid-feedback-shadcn d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6" id="reorderContainer" @if(old('ARCIM_OrderOnItsOwn', $item->ARCIM_OrderOnItsOwn) != 'Y') style="display: none;" @endif>
                    <div class="form-group-shadcn">
                        <label class="form-label-shadcn">Reorder On Its Own (ARCIM_ReorderOnItsOwn)</label>
                        <div class="d-flex align-items-center" style="gap: 0.5rem;">
                            <input type="checkbox" 
                                   class="form-check-input-shadcn @error('ARCIM_ReorderOnItsOwn') is-invalid @enderror" 
                                   id="ARCIM_ReorderOnItsOwn"
                                   {{ old('ARCIM_ReorderOnItsOwn', $item->ARCIM_ReorderOnItsOwn) == 'Y' ? 'checked' : '' }}
                                   onchange="handleReorderOnItsOwnChange()">
                            <input type="hidden" 
                                   name="ARCIM_ReorderOnItsOwn" 
                                   id="ARCIM_ReorderOnItsOwn_hidden"
                                   value="{{ old('ARCIM_ReorderOnItsOwn', $item->ARCIM_ReorderOnItsOwn) == 'Y' ? 'Y' : 'N' }}">
                            <label class="form-check-label-shadcn" for="ARCIM_ReorderOnItsOwn">
                                Enable Reorder On Its Own
                            </label>
                        </div>
                        @error('ARCIM_ReorderOnItsOwn')
                            <div class="invalid-feedback-shadcn d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Effective Dates --}}
                <div class="col-12 mt-3">
                    <div class="separator-shadcn"></div>
                    <h6 class="form-section-title-shadcn">Effective Dates</h6>
                </div>

                <div class="col-md-6">
                    <div class="form-group-shadcn">
                        <label class="form-label-shadcn" for="ARCIM_EffDate">Effective Date From (ARCIM_EffDate)</label>
                        <input type="date" 
                               id="ARCIM_EffDate"
                               name="ARCIM_EffDate" 
                               class="form-control-shadcn @error('ARCIM_EffDate') is-invalid @enderror" 
                               value="{{ old('ARCIM_EffDate', $item->ARCIM_EffDate ? $item->ARCIM_EffDate->format('Y-m-d') : '') }}">
                        @error('ARCIM_EffDate')
                            <div class="invalid-feedback-shadcn">{{ $message }}</div>
                        @enderror
                        <p class="form-description-shadcn">Start date when this item becomes active.</p>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group-shadcn">
                        <label class="form-label-shadcn" for="ARCIM_EffDateTo">Effective Date To (ARCIM_EffDateTo)</label>
                        <input type="date" 
                               id="ARCIM_EffDateTo"
                               name="ARCIM_EffDateTo" 
                               class="form-control-shadcn @error('ARCIM_EffDateTo') is-invalid @enderror" 
                               value="{{ old('ARCIM_EffDateTo', $item->ARCIM_EffDateTo ? $item->ARCIM_EffDateTo->format('Y-m-d') : '') }}">
                        @error('ARCIM_EffDateTo')
                            <div class="invalid-feedback-shadcn">{{ $message }}</div>
                        @enderror
                        <p class="form-description-shadcn">End date when this item becomes inactive (optional).</p>
                    </div>
                </div>
            </div>

            <!-- Separator -->
            <div class="separator-shadcn"></div>

            <!-- Action Buttons -->
            <div class="d-flex justify-content-end align-items-center" style="gap: 0.75rem;">
                <a href="{{ route('arc-itm-mast.index') }}" class="btn-shadcn btn-shadcn-outline">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg>
                    Batal
                </a>
                <button type="submit" class="btn-shadcn btn-shadcn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    Update Data
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Material margins data from PHP
const materialMarginsData = @json($materialMargins ?? []);

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

// Handle Type of Item Code field based on ARCIM_ServMaterial
document.addEventListener('DOMContentLoaded', function() {
    const servMaterialField = document.getElementById('ARCIM_ServMaterial');
    const typeOfItemContainer = document.querySelector('.form-group-shadcn:has(#TypeofItemCode)');
    
    if (!servMaterialField || !typeOfItemContainer) return;
    
    function updateTypeOfItemField() {
        const servMaterial = servMaterialField.value;
        const currentField = document.getElementById('TypeofItemCode');
        if (!currentField) return;
        
        const currentValue = currentField.value || '';
        const label = typeOfItemContainer.querySelector('label');
        const errorDiv = typeOfItemContainer.querySelector('.invalid-feedback-shadcn');
        
        // Get material margins data from global variable
        const materialMargins = materialMarginsData;
        
        if (servMaterial === 'M' && materialMargins.length > 0) {
            // Create dropdown
            const errorClass = currentField.classList.contains('is-invalid') ? ' is-invalid' : '';
            
            if (currentField.tagName === 'INPUT') {
                // Replace input with select
                const select = document.createElement('select');
                select.name = 'TypeofItemCode';
                select.id = 'TypeofItemCode';
                select.className = 'form-select-shadcn' + errorClass;
                
                const defaultOption = document.createElement('option');
                defaultOption.value = '';
                defaultOption.textContent = '-- Select Type of Item --';
                select.appendChild(defaultOption);
                
                materialMargins.forEach(function(margin) {
                    const option = document.createElement('option');
                    option.value = margin.TypeofItemCode;
                    option.textContent = margin.TypeofItemCode + ' - ' + (margin.TypeofItemDesc || '');
                    if (currentValue == margin.TypeofItemCode) {
                        option.selected = true;
                    }
                    select.appendChild(option);
                });
                
                currentField.parentNode.replaceChild(select, currentField);
            } else if (currentField.tagName === 'SELECT') {
                // Already a select, just update options while preserving value
                const selectedValue = currentField.value;
                currentField.innerHTML = '';
                
                const defaultOption = document.createElement('option');
                defaultOption.value = '';
                defaultOption.textContent = '-- Select Type of Item --';
                currentField.appendChild(defaultOption);
                
                materialMargins.forEach(function(margin) {
                    const option = document.createElement('option');
                    option.value = margin.TypeofItemCode;
                    option.textContent = margin.TypeofItemCode + ' - ' + (margin.TypeofItemDesc || '');
                    if (selectedValue == margin.TypeofItemCode || currentValue == margin.TypeofItemCode) {
                        option.selected = true;
                    }
                    currentField.appendChild(option);
                });
            }
        } else {
            // Create text input
            const errorClass = currentField.classList.contains('is-invalid') ? ' is-invalid' : '';
            
            if (currentField.tagName === 'SELECT') {
                // Replace select with input
                const input = document.createElement('input');
                input.type = 'text';
                input.id = 'TypeofItemCode';
                input.name = 'TypeofItemCode';
                input.className = 'form-control-shadcn' + errorClass;
                input.value = currentValue;
                input.placeholder = 'Enter Type of Item Code';
                
                currentField.parentNode.replaceChild(input, currentField);
            } else if (currentField.tagName === 'INPUT') {
                // Already an input, just update placeholder and ensure name attribute
                currentField.name = 'TypeofItemCode';
                currentField.placeholder = 'Enter Type of Item Code';
            }
        }
        
        // Re-attach error message if exists
        if (errorDiv && typeOfItemContainer.querySelector('.invalid-feedback-shadcn') === null) {
            typeOfItemContainer.appendChild(errorDiv);
        }
    }
    
    // Initial update
    updateTypeOfItemField();
    
    // Update on change
    servMaterialField.addEventListener('change', updateTypeOfItemField);
    
    // Ensure field has correct name attribute before form submit
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const typeOfItemField = document.getElementById('TypeofItemCode');
            if (typeOfItemField && !typeOfItemField.name) {
                typeOfItemField.name = 'TypeofItemCode';
            }
        });
    }
});

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
