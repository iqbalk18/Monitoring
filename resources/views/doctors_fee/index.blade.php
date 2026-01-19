@extends('layouts.app')

@section('title', 'Doctors Fee')

@section('content')
    <div class="container-fluid px-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-2 text-gray-800">Doctors Fee</h1>
                <p class="mb-0 text-muted">Manage and view doctors fee details.</p>
            </div>
            <div>
                <!-- Breadcrumb or Actions could go here -->
                <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                </a>
            </div>
        </div>

        <!-- Card -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Doctors Fee Data</h6>

                <!-- Search Form -->
                <form action="{{ route('doctors-fee.index') }}" method="GET"
                    class="d-none d-sm-inline-block form-inline ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control bg-light border-0 small"
                            placeholder="Search URN, Episode, Doctor..." aria-label="Search" aria-describedby="basic-addon2"
                            value="{{ request('search') }}">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="button">
                                <i class="fas fa-search fa-sm"></i> Search
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover" id="dataTable" width="100%"
                        cellspacing="0" style="white-space: nowrap; font-size: 0.85rem;">
                        <thead class="thead-light">
                            <tr>
                                <th>id</th>
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
                                <th>PercentDoctorAnesthesia</th>
                                <th>ResultDoctor</th>
                                <th>ResultDoctorAnesthesia</th>
                                <th>created_at</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($doctorsFees as $fee)
                                <tr>
                                    <td>{{ $fee->id }}</td>
                                    <td>{{ $fee->AdmDate }}</td>
                                    <td>{{ $fee->URN }}</td>
                                    <td>{{ $fee->FirstName }}</td>
                                    <td>{{ $fee->LastName }}</td>
                                    <td>{{ $fee->EpisodeNo }}</td>
                                    <td>{{ $fee->EpisodeType }}</td>
                                    <td>{{ $fee->Nationality }}</td>

                                    <td>{{ $fee->EpisodeDoctorCode }}</td>
                                    <td>{{ Str::limit($fee->EpisodeDoctorDesc, 20) }}</td>
                                    <td>{{ $fee->SpecialistTypeEpisode }}</td>
                                    <td>{{ $fee->EpisodeDoctorspecialist }}</td>

                                    <td>{{ $fee->OrderDoctorCode }}</td>
                                    <td>{{ Str::limit($fee->OrderDoctorDesc, 20) }}</td>
                                    <td>{{ $fee->SpecialistTypeOrder }}</td>
                                    <td>{{ $fee->OrderDoctorspecialist }}</td>

                                    <td>{{ $fee->ExecutedDoctorCode }}</td>
                                    <td>{{ Str::limit($fee->ExecutedDoctorDesc, 20) }}</td>
                                    <td>{{ $fee->SpecialistTypeExecuted }}</td>
                                    <td>{{ $fee->ExecutedDoctorspecialist }}</td>

                                    <td>{{ $fee->AuthoriseDoctorCode }}</td>
                                    <td>{{ Str::limit($fee->AuthoriseDoctorDesc, 20) }}</td>
                                    <td>{{ $fee->SpecialistTypeAuthorise }}</td>
                                    <td>{{ $fee->AuthoriseDoctorSpecialist }}</td>

                                    <td>{{ $fee->ConsultDoctorCode }}</td>
                                    <td>{{ Str::limit($fee->ConsultDoctorDesc, 20) }}</td>
                                    <td>{{ $fee->SpecialistTypeConsult }}</td>
                                    <td>{{ $fee->ConsultDoctorSpecialist }}</td>

                                    <td>{{ $fee->DoctorSurgeonCode }}</td>
                                    <td>{{ Str::limit($fee->DoctorSurgeryDesc, 20) }}</td>
                                    <td>{{ $fee->SpecialistType }}</td>
                                    <td>{{ $fee->DoctorSurgerySpecialist }}</td>

                                    <td>{{ $fee->PriorityDoctorCode }}</td>
                                    <td>{{ Str::limit($fee->PriorityDoctorDesc, 20) }}</td>
                                    <td>{{ $fee->PriorityDoctorSpecialist }}</td>

                                    <td>{{ $fee->DoctorAnaesCode }}</td>
                                    <td>{{ Str::limit($fee->DoctorAnaesDesc, 20) }}</td>
                                    <td>{{ $fee->DoctorAnaesSpecialist }}</td>

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
                                    <td>{{ number_format($fee->PercentDoctorAnesthesia, 2) }}%</td>
                                    <td>{{ number_format($fee->ResultDoctor, 2) }}</td>
                                    <td>{{ number_format($fee->ResultDoctorAnesthesia, 2) }}</td>

                                    <td>{{ $fee->created_at ? $fee->created_at->format('Y-m-d H:i') : '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="95" class="text-center">No Data Found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4 d-flex justify-content-end">
                    {{ $doctorsFees->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>

    <style>
        .table td,
        .table th {
            vertical-align: middle;
        }
    </style>
@endsection