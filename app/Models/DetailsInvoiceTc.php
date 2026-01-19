<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailsInvoiceTc extends Model
{
    use HasFactory;

    protected $table = 'details_invoice_tc';

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::created(function (DetailsInvoiceTc $details) {
            // Automatically copy data to DoctorsFee table upon creation
            // We exclude 'id', 'created_at', and 'updated_at' to let DoctorsFee handle its own ID and timestamps
            $data = $details->attributesToArray();
            unset($data['id'], $data['created_at'], $data['updated_at']);

            \App\Models\DoctorsFee::create($data);
        });
    }

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
        'OperationStartTime' => 'date:H:i:s', // Using date format for time only fields if they are actually times in DB, but casting to datetime usually better or string
        'OperationEndTime' => 'date:H:i:s',
        'PrintedInvoiceTime' => 'date:H:i:s',
        'InvoiceCancelledTime' => 'date:H:i:s',
        'ItemPrice' => 'decimal:2',
        'QTY' => 'decimal:2',
        'TotalPrice' => 'decimal:2',
        'DiscountItem' => 'decimal:2',
        'TotalPatient' => 'decimal:2',
        'TotalInsurance' => 'decimal:2',
    ];
}
