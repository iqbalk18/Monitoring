<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Ijd extends Model
{
    use HasFactory;


    protected $table = 'ijd';


    protected $fillable = [
        'AdmDate',
        'URN',
        'FirstName',
        'LastName',
        'EpisodeNo',
        'EpisodeType',
        'Nationality',
        'EpisodeDoctorCode',
        'EpisodeDoctorDesc',
        'SpecialistTypeEpisode',
        'EpisodeDoctorspecialist',
        'OrderDoctorCode',
        'OrderDoctorDesc',
        'SpecialistTypeOrder',
        'OrderDoctorspecialist',
        'ExecutedDoctorCode',
        'ExecutedDoctorDesc',
        'SpecialistTypeExecuted',
        'ExecutedDoctorspecialist',
        'AuthoriseDoctorCode',
        'AuthoriseDoctorDesc',
        'SpecialistTypeAuthorise',
        'AuthoriseDoctorSpecialist',
        'ConsultDoctorCode',
        'ConsultDoctorDesc',
        'SpecialistTypeConsult',
        'ConsultDoctorSpecialist',
        'DoctorSurgeonCode',
        'DoctorSurgeryDesc',
        'SpecialistType',
        'DoctorSurgerySpecialist',
        'DoctorAnaesCode',
        'DoctorAnaesDesc',
        'DoctorAnaesSpecialist',
        'ItemStatusCode',
        'ItemStatusDesc',
        'Payor',
        'ItemCode',
        'ItemDescription',
        'OperationCode',
        'OperationDesc',
        'ItemPrice',
        'QTY',
        'TotalPrice',
        'DiscountItem',
        'TypeofItem',
        'OrderSubCategoryCode',
        'OrderSubCategoryDesc',
        'OrderCategoryCode',
        'OrderCategoryDesc',
        'BillSubGroupCode',
        'BillSubGroupDesc',
        'BillingGroupCode',
        'BillingGroupDesc',
        'PackageCode',
        'PackageDescription',
        'OrCatPackageCode',
        'OrCatPackageDesc',
        'OrSubCatPackageCode',
        'OrSubCatPackageDesc',
        'DfrCode',
        'RowIdAppt',
        'StatusAppt',
        'OperationDate',
        'OrderDate',
        'InvoiceNumber',
        'TotalPatient',
        'TotalInsurance',
        'DischargeDate',
        'PrintedInvoiceDate',
        'PrintedInvoiceTime',
        'InvoiceCancelledDate',
        'InvoiceCancelledTime',
        'LocationOparation',
        'OperationNumber',
        'StatusOper',
        'AnasthesiaNumber',
        'StatusAnaes',
        'OperationStartDate',
        'OperationStartTime',
        'OperationEndDate',
        'OperationEndTime'
    ];


    protected $casts = [
        'AdmDate' => 'date',
        'OperationDate' => 'date',
        'OrderDate' => 'date',
        'DischargeDate' => 'date',
        'PrintedInvoiceDate' => 'date',
        'InvoiceCancelledDate' => 'date',
        'OperationStartDate' => 'date',
        'OperationEndDate' => 'date',
        'OperationStartTime' => 'datetime:H:i:s',
        'OperationEndTime' => 'datetime:H:i:s',
        'PrintedInvoiceTime' => 'datetime:H:i:s',
        'InvoiceCancelledTime' => 'datetime:H:i:s',
        'ItemPrice' => 'decimal:2',
        'TotalPrice' => 'decimal:2',
        'DiscountItem' => 'decimal:2',
        'TotalInsurance' => 'decimal:2',
    ];


    // Example: scopes or helper accessors can be added here


}