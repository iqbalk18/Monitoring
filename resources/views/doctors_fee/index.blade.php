@extends('layouts.app')

@section('title', 'Doctors Fee')

@push('styles')
    <style>
        /* Override for full width on this specific page */
        .container-shadcn {
            max-width: 100% !important;
            padding-left: 1.5rem;
            padding-right: 1.5rem;
        }

        .table-container-shadcn {
            max-height: 70vh;
            display: block;
            overflow-y: auto;
            overflow-x: auto;
            border-radius: var(--radius);
            border: 1px solid var(--border);
            position: relative;
        }

        /* Ensure table cells don't wrap too aggressively */
        .table-shadcn th,
        .table-shadcn td {
            white-space: nowrap;
            vertical-align: middle;
            padding: 0.75rem 1rem;
        }

        .table-shadcn thead {
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .table-shadcn thead th {
            background-color: var(--muted);
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            position: sticky;
            top: 0;
            /* Ensure header covers content when scrolling */
            box-shadow: 0 1px 0 var(--border);
        }

        .table-shadcn tbody td {
            font-size: 0.875rem;
        }
    </style>
@endpush

@section('content')
    <!-- Page Header -->
    <div class="flex-between mb-4">
        <div>
            <h2 class="section-title">Doctors Fee</h2>
            <p class="section-desc">Manage and view doctors fee details and calculations.</p>
        </div>
        <div>
            <a href="{{ route('dashboard') }}" class="btn-shadcn btn-shadcn-outline">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2">
                    <path d="m15 18-6-6 6-6" />
                </svg>
                Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Main Card -->
    <div class="card-shadcn">
        <div class="card-shadcn-header flex-between">
            <h3 class="card-shadcn-title">Doctors Fee Data</h3>

            <!-- Search Form -->
            <form action="{{ route('doctors-fee.index') }}" method="GET" class="d-flex align-items-center gap-2">
                <div class="input-group">
                    <input type="text" name="search" class="form-control-shadcn"
                        placeholder="Search URN, Episode, Doctor..." value="{{ request('search') }}"
                        style="min-width: 250px;">
                    <button class="btn-shadcn btn-shadcn-primary" type="submit">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="me-2">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" x2="16.65" y1="21" y2="16.65"></line>
                        </svg>
                        Search
                    </button>
                </div>
            </form>
        </div>

        <div class="card-shadcn-body">
            <div class="table-container-shadcn mb-4">
                <table class="table-shadcn table-striped w-100">
                    <thead>
                        <tr>
                            <th>AdmDate</th>
                            <th>URN</th>
                            <th>FirstName</th>
                            <th>LastName</th>
                            <th>EpisodeNo</th>
                            <th>EpisodeType</th>
                            <th>Nationality</th>

                            <th>EpisodeDoctorCode</th>
                            <th>EpisodeDoctorDesc</th>
                            <th>SpecialistTypeEpisode</th>
                            <th>EpisodeDoctorspecialist</th>

                            <th>OrderDoctorCode</th>
                            <th>OrderDoctorDesc</th>
                            <th>SpecialistTypeOrder</th>
                            <th>OrderDoctorspecialist</th>

                            <th>ExecutedDoctorCode</th>
                            <th>ExecutedDoctorDesc</th>
                            <th>SpecialistTypeExecuted</th>
                            <th>ExecutedDoctorspecialist</th>

                            <th>AuthoriseDoctorCode</th>
                            <th>AuthoriseDoctorDesc</th>
                            <th>SpecialistTypeAuthorise</th>
                            <th>AuthoriseDoctorSpecialist</th>

                            <th>ConsultDoctorCode</th>
                            <th>ConsultDoctorDesc</th>
                            <th>SpecialistTypeConsult</th>
                            <th>ConsultDoctorSpecialist</th>

                            <th>DoctorSurgeonCode</th>
                            <th>DoctorSurgeryDesc</th>
                            <th>SpecialistType</th>
                            <th>DoctorSurgerySpecialist</th>

                            <th>PriorityDoctorCode</th>
                            <th>PriorityDoctorDesc</th>
                            <th>PriorityDoctorSpecialist</th>

                            <th>DoctorAnaesCode</th>
                            <th>DoctorAnaesDesc</th>
                            <th>DoctorAnaesSpecialist</th>

                            <th>ReportingDoctorCode</th>
                            <th>ReportingDoctorDesc</th>
                            <th>SpecialistTypeReporting</th>
                            <th>DoctorReportingSpecialist</th>

                            <th>AdditionalDoctorCode</th>
                            <th>AdditionalDoctorDesc</th>
                            <th>SpecialistTypeAdditional</th>
                            <th>DoctorAdditoonalSpecialist</th>

                            <th>VerifiedDoctorCode</th>
                            <th>VerifiedDoctorDesc</th>
                            <th>SpecialistTypeVerified</th>
                            <th>DoctorVerifiedSpecialist</th>

                            <th>ItemStatusCode</th>
                            <th>ItemStatusDesc</th>
                            <th>Payor</th>
                            <th>ItemCode</th>
                            <th>ItemDescription</th>
                            <th>OperationCode</th>
                            <th>OperationDesc</th>
                            <th>ItemPrice</th>
                            <th>QTY</th>
                            <th>TotalPrice</th>
                            <th>DiscountItem</th>
                            <th>AfterDiscount</th>
                            <th>TypeofItem</th>
                            <th>UOMCode</th>
                            <th>UOMDesc</th>

                            <th>TestOrderId</th>
                            <th>AccessionNumber</th>
                            <th>TestCode</th>
                            <th>TestName</th>

                            <th>OrderSubCategoryCode</th>
                            <th>OrderSubCategoryDesc</th>
                            <th>OrderCategoryCode</th>
                            <th>OrderCategoryDesc</th>
                            <th>BillSubGroupCode</th>
                            <th>BillSubGroupDesc</th>
                            <th>BillingGroupCode</th>
                            <th>BillingGroupDesc</th>
                            <th>PackageCode</th>
                            <th>PackageDescription</th>
                            <th>OrCatPackageCode</th>
                            <th>OrCatPackageDesc</th>
                            <th>OrSubCatPackageCode</th>
                            <th>OrSubCatPackageDesc</th>
                            <th>DfrCode</th>
                            <th>RowIdAppt</th>
                            <th>StatusAppt</th>
                            <th>OperationDate</th>
                            <th>OrderDate</th>
                            <th>InvoiceNumber</th>
                            <th>TotalPatient</th>
                            <th>TotalInsurance</th>
                            <th>BatchNo</th>
                            <th>BatchDate</th>
                            <th>DischargeDate</th>
                            <th>PrintedInvoiceDate</th>
                            <th>PrintedInvoiceTime</th>
                            <th>InvoiceCancelledDate</th>
                            <th>InvoiceCancelledTime</th>
                            <th>LocationOparation</th>
                            <th>OperationNumber</th>
                            <th>StatusOper</th>
                            <th>AnasthesiaNumber</th>
                            <th>StatusAnaes</th>
                            <th>OperationStartDate</th>
                            <th>OperationStartTime</th>
                            <th>OperationEndDate</th>
                            <th>OperationEndTime</th>
                            <th>PercentDoctor</th>
                            <th>PercentDoctorAnaesthesia</th>
                            <th>ResultDoctor</th>
                            <th>ResultDoctorAneasthesia</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($doctorsFees as $fee)
                            <tr>
                                <td>{{ $fee->AdmDate }}</td>
                                <td>{{ $fee->URN }}</td>
                                <td>{{ $fee->FirstName }}</td>
                                <td>{{ $fee->LastName }}</td>
                                <td>{{ $fee->EpisodeNo }}</td>
                                <td>{{ $fee->EpisodeType }}</td>
                                <td>{{ $fee->Nationality }}</td>

                                <td>{{ $fee->EpisodeDoctorCode }}</td>
                                <td title="{{ $fee->EpisodeDoctorDesc }}">{{ Str::limit($fee->EpisodeDoctorDesc, 20) }}</td>
                                <td>{{ $fee->SpecialistTypeEpisode }}</td>
                                <td>{{ $fee->EpisodeDoctorspecialist }}</td>

                                <td>{{ $fee->OrderDoctorCode }}</td>
                                <td title="{{ $fee->OrderDoctorDesc }}">{{ Str::limit($fee->OrderDoctorDesc, 20) }}</td>
                                <td>{{ $fee->SpecialistTypeOrder }}</td>
                                <td>{{ $fee->OrderDoctorspecialist }}</td>

                                <td>{{ $fee->ExecutedDoctorCode }}</td>
                                <td title="{{ $fee->ExecutedDoctorDesc }}">{{ Str::limit($fee->ExecutedDoctorDesc, 20) }}</td>
                                <td>{{ $fee->SpecialistTypeExecuted }}</td>
                                <td>{{ $fee->ExecutedDoctorspecialist }}</td>

                                <td>{{ $fee->AuthoriseDoctorCode }}</td>
                                <td title="{{ $fee->AuthoriseDoctorDesc }}">{{ Str::limit($fee->AuthoriseDoctorDesc, 20) }}</td>
                                <td>{{ $fee->SpecialistTypeAuthorise }}</td>
                                <td>{{ $fee->AuthoriseDoctorSpecialist }}</td>

                                <td>{{ $fee->ConsultDoctorCode }}</td>
                                <td title="{{ $fee->ConsultDoctorDesc }}">{{ Str::limit($fee->ConsultDoctorDesc, 20) }}</td>
                                <td>{{ $fee->SpecialistTypeConsult }}</td>
                                <td>{{ $fee->ConsultDoctorSpecialist }}</td>

                                <td>{{ $fee->DoctorSurgeonCode }}</td>
                                <td title="{{ $fee->DoctorSurgeryDesc }}">{{ Str::limit($fee->DoctorSurgeryDesc, 20) }}</td>
                                <td>{{ $fee->SpecialistType }}</td>
                                <td>{{ $fee->DoctorSurgerySpecialist }}</td>

                                <td>{{ $fee->PriorityDoctorCode }}</td>
                                <td title="{{ $fee->PriorityDoctorDesc }}">{{ Str::limit($fee->PriorityDoctorDesc, 20) }}</td>
                                <td>{{ $fee->PriorityDoctorSpecialist }}</td>

                                <td>{{ $fee->DoctorAnaesCode }}</td>
                                <td title="{{ $fee->DoctorAnaesDesc }}">{{ Str::limit($fee->DoctorAnaesDesc, 20) }}</td>
                                <td>{{ $fee->DoctorAnaesSpecialist }}</td>

                                <td>{{ $fee->ReportingDoctorCode }}</td>
                                <td title="{{ $fee->ReportingDoctorDesc }}">{{ Str::limit($fee->ReportingDoctorDesc, 20) }}</td>
                                <td>{{ $fee->SpecialistTypeReporting }}</td>
                                <td>{{ $fee->DoctorReportingSpecialist }}</td>

                                <td>{{ $fee->AdditionalDoctorCode }}</td>
                                <td title="{{ $fee->AdditionalDoctorDesc }}">{{ Str::limit($fee->AdditionalDoctorDesc, 20) }}
                                </td>
                                <td>{{ $fee->SpecialistTypeAdditional }}</td>
                                <td>{{ $fee->DoctorAdditoonalSpecialist }}</td>

                                <td>{{ $fee->VerifiedDoctorCode }}</td>
                                <td title="{{ $fee->VerifiedDoctorDesc }}">{{ Str::limit($fee->VerifiedDoctorDesc, 20) }}</td>
                                <td>{{ $fee->SpecialistTypeVerified }}</td>
                                <td>{{ $fee->DoctorVerifiedSpecialist }}</td>

                                <td>{{ $fee->ItemStatusCode }}</td>
                                <td>{{ $fee->ItemStatusDesc }}</td>
                                <td>{{ $fee->Payor }}</td>
                                <td>{{ $fee->ItemCode }}</td>
                                <td title="{{ $fee->ItemDescription }}">{{ Str::limit($fee->ItemDescription, 30) }}</td>
                                <td>{{ $fee->OperationCode }}</td>
                                <td title="{{ $fee->OperationDesc }}">{{ Str::limit($fee->OperationDesc, 30) }}</td>

                                <td>{{ number_format($fee->ItemPrice, 2) }}</td>
                                <td>{{ number_format($fee->QTY, 2) }}</td>
                                <td>{{ number_format($fee->TotalPrice, 2) }}</td>
                                <td>{{ number_format($fee->DiscountItem, 2) }}</td>
                                <td>{{ number_format($fee->AfterDiscount, 2) }}</td>

                                <td>{{ $fee->TypeofItem }}</td>
                                <td>{{ $fee->UOMCode }}</td>
                                <td>{{ $fee->UOMDesc }}</td>

                                <td>{{ $fee->TestOrderId }}</td>
                                <td>{{ $fee->AccessionNumber }}</td>
                                <td>{{ $fee->TestCode }}</td>
                                <td>{{ $fee->TestName }}</td>

                                <td>{{ $fee->OrderSubCategoryCode }}</td>
                                <td>{{ $fee->OrderSubCategoryDesc }}</td>
                                <td>{{ $fee->OrderCategoryCode }}</td>
                                <td>{{ $fee->OrderCategoryDesc }}</td>
                                <td>{{ $fee->BillSubGroupCode }}</td>
                                <td>{{ $fee->BillSubGroupDesc }}</td>
                                <td>{{ $fee->BillingGroupCode }}</td>
                                <td>{{ $fee->BillingGroupDesc }}</td>

                                <td>{{ $fee->PackageCode }}</td>
                                <td title="{{ $fee->PackageDescription }}">{{ Str::limit($fee->PackageDescription, 20) }}
                                </td>
                                <td>{{ $fee->OrCatPackageCode }}</td>
                                <td>{{ $fee->OrCatPackageDesc }}</td>
                                <td>{{ $fee->OrSubCatPackageCode }}</td>
                                <td>{{ $fee->OrSubCatPackageDesc }}</td>

                                <td>{{ $fee->DfrCode }}</td>
                                <td>{{ $fee->RowIdAppt }}</td>
                                <td>{{ $fee->StatusAppt }}</td>

                                <td>{{ $fee->OperationDate }}</td>
                                <td>{{ $fee->OrderDate }}</td>
                                <td>{{ $fee->InvoiceNumber }}</td>
                                <td>{{ number_format($fee->TotalPatient, 2) }}</td>
                                <td>{{ number_format($fee->TotalInsurance, 2) }}</td>

                                <td>{{ $fee->BatchNo }}</td>
                                <td>{{ $fee->BatchDate }}</td>

                                <td>{{ $fee->DischargeDate }}</td>
                                <td>{{ $fee->PrintedInvoiceDate }}</td>
                                <td>{{ $fee->PrintedInvoiceTime }}</td>
                                <td>{{ $fee->InvoiceCancelledDate }}</td>
                                <td>{{ $fee->InvoiceCancelledTime }}</td>

                                <td>{{ $fee->LocationOparation }}</td>
                                <td>{{ $fee->OperationNumber }}</td>
                                <td>{{ $fee->StatusOper }}</td>
                                <td>{{ $fee->AnasthesiaNumber }}</td>
                                <td>{{ $fee->StatusAnaes }}</td>
                                <td>{{ $fee->OperationStartDate }}</td>
                                <td>{{ $fee->OperationStartTime }}</td>
                                <td>{{ $fee->OperationEndDate }}</td>
                                <td>{{ $fee->OperationEndTime }}</td>

                                <td>{{ number_format($fee->PercentDoctor, 2) }}%</td>
                                <td>{{ number_format($fee->PercentDoctorAnaesthesia, 2) }}%</td>
                                <td>{{ number_format($fee->ResultDoctor, 2) }}</td>
                                <td>{{ number_format($fee->ResultDoctorAneasthesia, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="95" class="text-center text-muted py-4">
                                    No Data Found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-end">
                {{ $doctorsFees->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection