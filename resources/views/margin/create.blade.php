@extends('layouts.app')

@section('title', 'Tambah Data Margin - Bali International Hospital')

@section('content')
    <!-- Page Header -->
    <div class="flex-between mb-4" style="flex-wrap: wrap; gap: 1rem;">
        <div>
            <h2 class="section-title">Add Margin Data</h2>
            <p class="section-desc">Create new margin configuration for pricing calculation.</p>
        </div>
        <div class="d-flex align-items-center" style="gap: 0.5rem;">
            <a href="{{ route('margin.index') }}" class="btn-shadcn btn-shadcn-outline">
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
    <div class="card-shadcn">
        <div class="card-shadcn-header">
            <h3 class="card-shadcn-title">Margin Information</h3>
            <p class="card-shadcn-description">Fill in the margin details below.</p>
        </div>
        <div class="card-shadcn-body">
            <form action="{{ route('margin.store') }}" method="POST">
                @csrf

                <div class="row" style="row-gap: 1.5rem;">
                    <!-- TypeofItem Code -->
                    <div class="col-md-6">
                        <div class="form-group-shadcn">
                            <label class="form-label-shadcn" for="TypeofItemCode">
                                TypeofItem Code
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" id="TypeofItemCode" name="TypeofItemCode"
                                class="form-control-shadcn @error('TypeofItemCode') is-invalid @enderror"
                                value="{{ old('TypeofItemCode') }}" placeholder="Enter TypeofItem Code" required>
                            @error('TypeofItemCode')
                                <div class="invalid-feedback-shadcn">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- TypeofItem Description -->
                    <div class="col-md-6">
                        <div class="form-group-shadcn">
                            <label class="form-label-shadcn" for="TypeofItemDesc">
                                TypeofItem Description
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" id="TypeofItemDesc" name="TypeofItemDesc"
                                class="form-control-shadcn @error('TypeofItemDesc') is-invalid @enderror"
                                value="{{ old('TypeofItemDesc') }}" placeholder="Enter description" required>
                            @error('TypeofItemDesc')
                                <div class="invalid-feedback-shadcn">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Margin -->
                    <div class="col-md-6">
                        <div class="form-group-shadcn">
                            <label class="form-label-shadcn" for="Margin">
                                Margin (%)
                            </label>
                            <div class="input-group-shadcn">
                                <input type="number" id="Margin" step="0.01" min="0" max="1000" name="Margin"
                                    class="form-control-shadcn @error('Margin') is-invalid @enderror"
                                    value="{{ old('Margin') }}" placeholder="0.00">
                            </div>
                            @error('Margin')
                                <div class="invalid-feedback-shadcn">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- ARCIM_ServMateria -->
                    <div class="col-md-6">
                        <div class="form-group-shadcn">
                            <label class="form-label-shadcn" for="ARCIM_ServMateria">
                                ARCIM_ServMateria
                                <span class="text-danger">*</span>
                            </label>
                            <select id="ARCIM_ServMateria" name="ARCIM_ServMateria"
                                class="form-select-shadcn @error('ARCIM_ServMateria') is-invalid @enderror" required>
                                <option value="">-- Select Type --</option>
                                <option value="S" {{ old('ARCIM_ServMateria') == 'S' ? 'selected' : '' }}>S - Service</option>
                                <option value="M" {{ old('ARCIM_ServMateria') == 'M' ? 'selected' : '' }}>M - Material
                                </option>
                            </select>
                            @error('ARCIM_ServMateria')
                                <div class="invalid-feedback-shadcn">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Separator -->
                <div class="separator-shadcn"></div>

                <!-- Action Buttons -->
                <div class="d-flex justify-content-end align-items-center mt-4" style="gap: 0.75rem;">
                    <a href="{{ route('margin.index') }}" class="btn-shadcn btn-shadcn-outline">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" x2="6" y1="6" y2="18" />
                            <line x1="6" x2="18" y1="6" y2="18" />
                        </svg>
                        Cancel
                    </a>
                    <button type="submit" class="btn-shadcn btn-shadcn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                            <polyline points="17 21 17 13 7 13 7 21" />
                            <polyline points="7 3 7 8 15 8" />
                        </svg>
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection