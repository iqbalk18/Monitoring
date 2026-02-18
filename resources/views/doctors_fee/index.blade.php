@extends('layouts.app')

@section('content')
    <style>
        .container-shadcn {
            max-width: 95% !important;
        }
    </style>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Doctors Fee</h4>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" style="white-space: nowrap;">
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
                                    <th>PriorityDoctorCode</th>
                                    <th>PriorityDoctorDesc</th>
                                    <th>PriorityDoctorSpecialist</th>
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
                                    <th>AfterDisc</th>
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
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($doctorsFees as $item)
                                    <tr>
                                        <td>{{ $item->AdmDate }}</td>
                                        <td>{{ $item->URN }}</td>
                                        <td>{{ $item->FirstName }}</td>
                                        <td>{{ $item->LastName }}</td>
                                        <td>{{ $item->EpisodeNo }}</td>
                                        <td>{{ $item->EpisodeType }}</td>
                                        <td>{{ $item->Nationality }}</td>
                                        <td>{{ $item->EpisodeDoctorCode }}</td>
                                        <td>{{ $item->EpisodeDoctorDesc }}</td>
                                        <td>{{ $item->SpecialistTypeEpisode }}</td>
                                        <td>{{ $item->EpisodeDoctorspecialist }}</td>
                                        <td>{{ $item->OrderDoctorCode }}</td>
                                        <td>{{ $item->OrderDoctorDesc }}</td>
                                        <td>{{ $item->SpecialistTypeOrder }}</td>
                                        <td>{{ $item->OrderDoctorspecialist }}</td>
                                        <td>{{ $item->ExecutedDoctorCode }}</td>
                                        <td>{{ $item->ExecutedDoctorDesc }}</td>
                                        <td>{{ $item->SpecialistTypeExecuted }}</td>
                                        <td>{{ $item->ExecutedDoctorspecialist }}</td>
                                        <td>{{ $item->AuthoriseDoctorCode }}</td>
                                        <td>{{ $item->AuthoriseDoctorDesc }}</td>
                                        <td>{{ $item->SpecialistTypeAuthorise }}</td>
                                        <td>{{ $item->AuthoriseDoctorSpecialist }}</td>
                                        <td>{{ $item->ConsultDoctorCode }}</td>
                                        <td>{{ $item->ConsultDoctorDesc }}</td>
                                        <td>{{ $item->SpecialistTypeConsult }}</td>
                                        <td>{{ $item->ConsultDoctorSpecialist }}</td>
                                        <td>{{ $item->DoctorSurgeonCode }}</td>
                                        <td>{{ $item->DoctorSurgeryDesc }}</td>
                                        <td>{{ $item->SpecialistType }}</td>
                                        <td>{{ $item->DoctorSurgerySpecialist }}</td>
                                        <td>{{ $item->DoctorAnaesCode }}</td>
                                        <td>{{ $item->DoctorAnaesDesc }}</td>
                                        <td>{{ $item->DoctorAnaesSpecialist }}</td>
                                        <td>{{ $item->ReportingDoctorCode }}</td>
                                        <td>{{ $item->ReportingDoctorDesc }}</td>
                                        <td>{{ $item->SpecialistTypeReporting }}</td>
                                        <td>{{ $item->DoctorReportingSpecialist }}</td>
                                        <td>{{ $item->AdditionalDoctorCode }}</td>
                                        <td>{{ $item->AdditionalDoctorDesc }}</td>
                                        <td>{{ $item->SpecialistTypeAdditional }}</td>
                                        <td>{{ $item->DoctorAdditoonalSpecialist }}</td>
                                        <td>{{ $item->VerifiedDoctorCode }}</td>
                                        <td>{{ $item->VerifiedDoctorDesc }}</td>
                                        <td>{{ $item->SpecialistTypeVerified }}</td>
                                        <td>{{ $item->DoctorVerifiedSpecialist }}</td>
                                        <td>{{ $item->PriorityDoctorCode }}</td>
                                        <td>{{ $item->PriorityDoctorDesc }}</td>
                                        <td>{{ $item->PriorityDoctorSpecialist }}</td>
                                        <td>{{ $item->ItemStatusCode }}</td>
                                        <td>{{ $item->ItemStatusDesc }}</td>
                                        <td>{{ $item->Payor }}</td>
                                        <td>{{ $item->ItemCode }}</td>
                                        <td>{{ $item->ItemDescription }}</td>
                                        <td>{{ $item->OperationCode }}</td>
                                        <td>{{ $item->OperationDesc }}</td>
                                        <td>{{ $item->ItemPrice }}</td>
                                        <td>{{ $item->QTY }}</td>
                                        <td>{{ $item->TotalPrice }}</td>
                                        <td>{{ $item->DiscountItem }}</td>
                                        <td>{{ $item->AfterDisc }}</td>
                                        <td>{{ $item->TypeofItem }}</td>
                                        <td>{{ $item->UOMCode }}</td>
                                        <td>{{ $item->UOMDesc }}</td>
                                        <td>{{ $item->TestOrderId }}</td>
                                        <td>{{ $item->AccessionNumber }}</td>
                                        <td>{{ $item->TestCode }}</td>
                                        <td>{{ $item->TestName }}</td>
                                        <td>{{ $item->OrderSubCategoryCode }}</td>
                                        <td>{{ $item->OrderSubCategoryDesc }}</td>
                                        <td>{{ $item->OrderCategoryCode }}</td>
                                        <td>{{ $item->OrderCategoryDesc }}</td>
                                        <td>{{ $item->BillSubGroupCode }}</td>
                                        <td>{{ $item->BillSubGroupDesc }}</td>
                                        <td>{{ $item->BillingGroupCode }}</td>
                                        <td>{{ $item->BillingGroupDesc }}</td>
                                        <td>{{ $item->PackageCode }}</td>
                                        <td>{{ $item->PackageDescription }}</td>
                                        <td>{{ $item->OrCatPackageCode }}</td>
                                        <td>{{ $item->OrCatPackageDesc }}</td>
                                        <td>{{ $item->OrSubCatPackageCode }}</td>
                                        <td>{{ $item->OrSubCatPackageDesc }}</td>
                                        <td>{{ $item->DfrCode }}</td>
                                        <td>{{ $item->RowIdAppt }}</td>
                                        <td>{{ $item->StatusAppt }}</td>
                                        <td>{{ $item->OperationDate }}</td>
                                        <td>{{ $item->OrderDate }}</td>
                                        <td>{{ $item->InvoiceNumber }}</td>
                                        <td>{{ $item->TotalPatient }}</td>
                                        <td>{{ $item->TotalInsurance }}</td>
                                        <td>{{ $item->BatchNo }}</td>
                                        <td>{{ $item->BatchDate }}</td>
                                        <td>{{ $item->DischargeDate }}</td>
                                        <td>{{ $item->PrintedInvoiceDate }}</td>
                                        <td>{{ $item->PrintedInvoiceTime }}</td>
                                        <td>{{ $item->InvoiceCancelledDate }}</td>
                                        <td>{{ $item->InvoiceCancelledTime }}</td>
                                        <td>{{ $item->LocationOparation }}</td>
                                        <td>{{ $item->OperationNumber }}</td>
                                        <td>{{ $item->StatusOper }}</td>
                                        <td>{{ $item->AnasthesiaNumber }}</td>
                                        <td>{{ $item->StatusAnaes }}</td>
                                        <td>{{ $item->OperationStartDate }}</td>
                                        <td>{{ $item->OperationStartTime }}</td>
                                        <td>{{ $item->OperationEndDate }}</td>
                                        <td>{{ $item->OperationEndTime }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $doctorsFees->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection