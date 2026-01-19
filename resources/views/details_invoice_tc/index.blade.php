@extends('layouts.app')

@section('title', 'Details Invoice TC - Bali International Hospital')

@section('content')
    <!-- Page Header -->
    <div class="flex-between mb-4" style="flex-wrap: wrap; gap: 1rem;">
        <div>
            <h2 class="section-title">Details Invoice TC</h2>
            <p class="section-desc">Manage Invoice Details records.</p>
        </div>
        <div class="d-flex align-items-center" style="gap: 0.5rem;">
            <a href="{{ route('dashboard') }}" class="btn-shadcn btn-shadcn-outline">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m12 19-7-7 7-7" />
                    <path d="M19 12H5" />
                </svg>
                Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Search/Filter Section -->
    <div class="card-shadcn mb-4">
        <div class="card-shadcn-body">
            <form method="GET" action="{{ route('details-invoice-tc.index') }}">
                <div class="d-flex flex-wrap align-items-center" style="gap: 0.75rem;">
                    <div class="flex-grow-1">
                        <input type="text" name="search" class="form-control-shadcn"
                            placeholder="Search by URN, Episode No, Name, or Invoice..." value="{{ request('search') }}"
                            style="width: 100%;">
                    </div>
                    <button type="submit" class="btn-shadcn btn-shadcn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8" />
                            <path d="m21 21-4.3-4.3" />
                        </svg>
                        Search
                    </button>
                    @if(request('search'))
                        <a href="{{ route('details-invoice-tc.index') }}" class="btn-shadcn btn-shadcn-outline">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8" />
                                <path d="M21 3v5h-5" />
                                <path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16" />
                                <path d="M8 16H3v5" />
                            </svg>
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Data Table -->
    <div class="card-shadcn">
        <div class="card-shadcn-header flex-between">
            <div class="d-flex align-items-center" style="gap: 0.75rem;">
                <h3 class="card-shadcn-title mb-0">Invoice Details</h3>
                <span class="badge-shadcn badge-shadcn-secondary">{{ $details->total() }} records</span>
            </div>
        </div>
        <div class="card-shadcn-body" style="padding: 0;">
            <div class="table-container-shadcn" style="border: none; border-radius: 0; overflow-x: auto;">
                <table class="table-shadcn w-100" style="white-space: nowrap;">
                    <thead>
                        <tr>
                            <th>Adm Date</th>
                            <th>URN</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Episode No</th>
                            <th>Episode Type</th>
                            <th>Nationality</th>
                            <th>Episode Doctor Code</th>
                            <th>Episode Doctor Desc</th>
                            <th>Specialist Type Episode</th>
                            <th>Episode Doctor Specialist</th>
                            <th>Order Doctor Code</th>
                            <th>Order Doctor Desc</th>
                            <th>Specialist Type Order</th>
                            <th>Order Doctor Specialist</th>
                            <th>Executed Doctor Code</th>
                            <th>Executed Doctor Desc</th>
                            <th>Specialist Type Executed</th>
                            <th>Executed Doctor Specialist</th>
                            <th>Authorise Doctor Code</th>
                            <th>Authorise Doctor Desc</th>
                            <th>Specialist Type Authorise</th>
                            <th>Authorise Doctor Specialist</th>
                            <th>Consult Doctor Code</th>
                            <th>Consult Doctor Desc</th>
                            <th>Specialist Type Consult</th>
                            <th>Consult Doctor Specialist</th>
                            <th>Doctor Surgeon Code</th>
                            <th>Doctor Surgery Desc</th>
                            <th>Specialist Type</th>
                            <th>Doctor Surgery Specialist</th>
                            <th>Doctor Anaes Code</th>
                            <th>Doctor Anaes Desc</th>
                            <th>Doctor Anaes Specialist</th>
                            <th>Item Status Code</th>
                            <th>Item Status Desc</th>
                            <th>Payor</th>
                            <th>Item Code</th>
                            <th>Item Description</th>
                            <th>Operation Code</th>
                            <th>Operation Desc</th>
                            <th>Item Price</th>
                            <th>QTY</th>
                            <th>Total Price</th>
                            <th>Discount Item</th>
                            <th>Type of Item</th>
                            <th>Order Sub Category Code</th>
                            <th>Order Sub Category Desc</th>
                            <th>Order Category Code</th>
                            <th>Order Category Desc</th>
                            <th>Bill Sub Group Code</th>
                            <th>Bill Sub Group Desc</th>
                            <th>Billing Group Code</th>
                            <th>Billing Group Desc</th>
                            <th>Package Code</th>
                            <th>Package Description</th>
                            <th>Or Cat Package Code</th>
                            <th>Or Cat Package Desc</th>
                            <th>Or Sub Cat Package Code</th>
                            <th>Or Sub Cat Package Desc</th>
                            <th>Dfr Code</th>
                            <th>Row Id Appt</th>
                            <th>Status Appt</th>
                            <th>Operation Date</th>
                            <th>Order Date</th>
                            <th>Invoice Number</th>
                            <th>Total Patient</th>
                            <th>Total Insurance</th>
                            <th>Discharge Date</th>
                            <th>Printed Invoice Date</th>
                            <th>Printed Invoice Time</th>
                            <th>Invoice Cancelled Date</th>
                            <th>Invoice Cancelled Time</th>
                            <th>Location Oparation</th>
                            <th>Operation Number</th>
                            <th>Status Oper</th>
                            <th>Anasthesia Number</th>
                            <th>Status Anaes</th>
                            <th>Operation Start Date</th>
                            <th>Operation Start Time</th>
                            <th>Operation End Date</th>
                            <th>Operation End Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($details as $detail)
                            <tr>
                                <td>{{ $detail->AdmDate ? $detail->AdmDate->format('d/m/Y') : '-' }}</td>
                                <td><code style="font-size: 0.8125rem;">{{ $detail->URN }}</code></td>
                                <td>{{ $detail->FirstName }}</td>
                                <td>{{ $detail->LastName }}</td>
                                <td>{{ $detail->EpisodeNo }}</td>
                                <td>{{ $detail->EpisodeType }}</td>
                                <td>{{ $detail->Nationality }}</td>
                                <td>{{ $detail->EpisodeDoctorCode }}</td>
                                <td>{{ $detail->EpisodeDoctorDesc }}</td>
                                <td>{{ $detail->SpecialistTypeEpisode }}</td>
                                <td>{{ $detail->EpisodeDoctorspecialist }}</td>
                                <td>{{ $detail->OrderDoctorCode }}</td>
                                <td>{{ $detail->OrderDoctorDesc }}</td>
                                <td>{{ $detail->SpecialistTypeOrder }}</td>
                                <td>{{ $detail->OrderDoctorspecialist }}</td>
                                <td>{{ $detail->ExecutedDoctorCode }}</td>
                                <td>{{ $detail->ExecutedDoctorDesc }}</td>
                                <td>{{ $detail->SpecialistTypeExecuted }}</td>
                                <td>{{ $detail->ExecutedDoctorspecialist }}</td>
                                <td>{{ $detail->AuthoriseDoctorCode }}</td>
                                <td>{{ $detail->AuthoriseDoctorDesc }}</td>
                                <td>{{ $detail->SpecialistTypeAuthorise }}</td>
                                <td>{{ $detail->AuthoriseDoctorSpecialist }}</td>
                                <td>{{ $detail->ConsultDoctorCode }}</td>
                                <td>{{ $detail->ConsultDoctorDesc }}</td>
                                <td>{{ $detail->SpecialistTypeConsult }}</td>
                                <td>{{ $detail->ConsultDoctorSpecialist }}</td>
                                <td>{{ $detail->DoctorSurgeonCode }}</td>
                                <td>{{ $detail->DoctorSurgeryDesc }}</td>
                                <td>{{ $detail->SpecialistType }}</td>
                                <td>{{ $detail->DoctorSurgerySpecialist }}</td>
                                <td>{{ $detail->DoctorAnaesCode }}</td>
                                <td>{{ $detail->DoctorAnaesDesc }}</td>
                                <td>{{ $detail->DoctorAnaesSpecialist }}</td>
                                <td>{{ $detail->ItemStatusCode }}</td>
                                <td>{{ $detail->ItemStatusDesc }}</td>
                                <td>{{ $detail->Payor }}</td>
                                <td><span class="badge-shadcn badge-shadcn-secondary">{{ $detail->ItemCode }}</span></td>
                                <td title="{{ $detail->ItemDescription }}">{{ Str::limit($detail->ItemDescription, 30) }}</td>
                                <td>{{ $detail->OperationCode }}</td>
                                <td>{{ $detail->OperationDesc }}</td>
                                <td>{{ number_format($detail->ItemPrice, 2) }}</td>
                                <td>{{ $detail->QTY }}</td>
                                <td>{{ number_format($detail->TotalPrice, 2) }}</td>
                                <td>{{ number_format($detail->DiscountItem, 2) }}</td>
                                <td>{{ $detail->TypeofItem }}</td>
                                <td>{{ $detail->OrderSubCategoryCode }}</td>
                                <td>{{ $detail->OrderSubCategoryDesc }}</td>
                                <td>{{ $detail->OrderCategoryCode }}</td>
                                <td>{{ $detail->OrderCategoryDesc }}</td>
                                <td>{{ $detail->BillSubGroupCode }}</td>
                                <td>{{ $detail->BillSubGroupDesc }}</td>
                                <td>{{ $detail->BillingGroupCode }}</td>
                                <td>{{ $detail->BillingGroupDesc }}</td>
                                <td>{{ $detail->PackageCode }}</td>
                                <td>{{ $detail->PackageDescription }}</td>
                                <td>{{ $detail->OrCatPackageCode }}</td>
                                <td>{{ $detail->OrCatPackageDesc }}</td>
                                <td>{{ $detail->OrSubCatPackageCode }}</td>
                                <td>{{ $detail->OrSubCatPackageDesc }}</td>
                                <td>{{ $detail->DfrCode }}</td>
                                <td>{{ $detail->RowIdAppt }}</td>
                                <td>{{ $detail->StatusAppt }}</td>
                                <td>{{ $detail->OperationDate ? $detail->OperationDate->format('d/m/Y') : '-' }}</td>
                                <td>{{ $detail->OrderDate ? $detail->OrderDate->format('d/m/Y') : '-' }}</td>
                                <td>{{ $detail->InvoiceNumber }}</td>
                                <td>{{ number_format($detail->TotalPatient, 2) }}</td>
                                <td>{{ number_format($detail->TotalInsurance, 2) }}</td>
                                <td>{{ $detail->DischargeDate ? $detail->DischargeDate->format('d/m/Y') : '-' }}</td>
                                <td>{{ $detail->PrintedInvoiceDate ? $detail->PrintedInvoiceDate->format('d/m/Y') : '-' }}</td>
                                <td>{{ $detail->PrintedInvoiceTime ? $detail->PrintedInvoiceTime->format('H:i:s') : '-' }}</td>
                                <td>{{ $detail->InvoiceCancelledDate ? $detail->InvoiceCancelledDate->format('d/m/Y') : '-' }}</td>
                                <td>{{ $detail->InvoiceCancelledTime ? $detail->InvoiceCancelledTime->format('H:i:s') : '-' }}</td>
                                <td>{{ $detail->LocationOparation }}</td>
                                <td>{{ $detail->OperationNumber }}</td>
                                <td>{{ $detail->StatusOper }}</td>
                                <td>{{ $detail->AnasthesiaNumber }}</td>
                                <td>{{ $detail->StatusAnaes }}</td>
                                <td>{{ $detail->OperationStartDate ? $detail->OperationStartDate->format('d/m/Y') : '-' }}</td>
                                <td>{{ $detail->OperationStartTime ? $detail->OperationStartTime->format('H:i:s') : '-' }}</td>
                                <td>{{ $detail->OperationEndDate ? $detail->OperationEndDate->format('d/m/Y') : '-' }}</td>
                                <td>{{ $detail->OperationEndTime ? $detail->OperationEndTime->format('H:i:s') : '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="80" class="text-center" style="padding: 3rem;">
                                    <div style="color: var(--muted-foreground);">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round"
                                            stroke-linejoin="round" style="opacity: 0.5; margin-bottom: 1rem;">
                                            <rect width="18" height="18" x="3" y="3" rx="2" ry="2" />
                                            <line x1="3" x2="21" y1="9" y2="9" />
                                            <line x1="9" x2="9" y1="21" y2="9" />
                                        </svg>
                                        <p class="mb-0" style="font-size: 0.875rem;">No details found</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($details->hasPages())
            <div class="card-shadcn-footer">
                <div class="d-flex align-items-center justify-content-between">
                    <div style="font-size: 0.875rem; color: var(--muted-foreground);">
                        Showing {{ $details->firstItem() }}-{{ $details->lastItem() }} of {{ $details->total() }} records
                    </div>
                    <div>
                        {{ $details->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
