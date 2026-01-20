<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorsFee extends Model
{
    use HasFactory;

    protected static function booted(): void
    {
        static::saving(function (DoctorsFee $doctorsFee) {
            $totalPrice = $doctorsFee->TotalPrice ?? 0;
            $discountItem = $doctorsFee->DiscountItem ?? 0;
            $doctorsFee->AfterDiscount = $totalPrice - $discountItem;
        });
    }

    protected $table = 'doctors_fee';

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
        'PriorityDoctorCode',
        'PriorityDoctorDesc',
        'PriorityDoctorSpecialist',
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
        'AfterDiscount',
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
        'OperationEndTime',
        'PercentDoctor',
        'PercentDoctorAnesthesia',
        'ResultDoctor',
        'ResultDoctorAnesthesia'
    ];

    protected $casts = [
        'ItemPrice' => 'decimal:2',
        'QTY' => 'decimal:2',
        'TotalPrice' => 'decimal:2',
        'DiscountItem' => 'decimal:2',
        'AfterDiscount' => 'decimal:2',
        'TotalPatient' => 'decimal:2',
        'TotalInsurance' => 'decimal:2',
        'PercentDoctor' => 'decimal:2',
        'PercentDoctorAnesthesia' => 'decimal:2',
        'ResultDoctor' => 'decimal:2',
        'ResultDoctorAnesthesia' => 'decimal:2',
    ];
}
