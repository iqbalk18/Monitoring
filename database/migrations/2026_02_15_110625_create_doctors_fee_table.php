<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('doctors_fee', function (Blueprint $table) {
            $table->id();
            $table->date('AdmDate')->nullable();
            $table->string('URN', 100)->nullable();
            $table->string('FirstName', 100)->nullable();
            $table->string('LastName', 100)->nullable();
            $table->string('EpisodeNo', 100)->nullable();
            $table->string('EpisodeType', 100)->nullable();
            $table->string('Nationality', 100)->nullable();
            $table->string('EpisodeDoctorCode', 100)->nullable();
            $table->text('EpisodeDoctorDesc')->nullable();
            $table->string('SpecialistTypeEpisode', 100)->nullable();
            $table->text('EpisodeDoctorspecialist')->nullable();
            $table->string('OrderDoctorCode', 100)->nullable();
            $table->text('OrderDoctorDesc')->nullable();
            $table->string('SpecialistTypeOrder', 100)->nullable();
            $table->text('OrderDoctorspecialist')->nullable();
            $table->string('ExecutedDoctorCode', 100)->nullable();
            $table->text('ExecutedDoctorDesc')->nullable();
            $table->string('SpecialistTypeExecuted', 100)->nullable();
            $table->text('ExecutedDoctorspecialist')->nullable();
            $table->string('AuthoriseDoctorCode', 100)->nullable();
            $table->text('AuthoriseDoctorDesc')->nullable();
            $table->string('SpecialistTypeAuthorise', 100)->nullable();
            $table->text('AuthoriseDoctorSpecialist')->nullable();
            $table->string('ConsultDoctorCode', 100)->nullable();
            $table->text('ConsultDoctorDesc')->nullable();
            $table->string('SpecialistTypeConsult', 100)->nullable();
            $table->text('ConsultDoctorSpecialist')->nullable();
            $table->string('DoctorSurgeonCode', 100)->nullable();
            $table->text('DoctorSurgeryDesc')->nullable();
            $table->string('SpecialistType', 100)->nullable();
            $table->text('DoctorSurgerySpecialist')->nullable();
            $table->string('DoctorAnaesCode', 100)->nullable();
            $table->text('DoctorAnaesDesc')->nullable();
            $table->text('DoctorAnaesSpecialist')->nullable();
            $table->string('ReportingDoctorCode', 100)->nullable();
            $table->text('ReportingDoctorDesc')->nullable();
            $table->string('SpecialistTypeReporting', 100)->nullable();
            $table->text('DoctorReportingSpecialist')->nullable();
            $table->string('AdditionalDoctorCode', 100)->nullable();
            $table->text('AdditionalDoctorDesc')->nullable();
            $table->string('SpecialistTypeAdditional', 100)->nullable();
            $table->text('DoctorAdditoonalSpecialist')->nullable();
            $table->string('VerifiedDoctorCode', 100)->nullable();
            $table->text('VerifiedDoctorDesc')->nullable();
            $table->string('SpecialistTypeVerified', 100)->nullable();
            $table->text('DoctorVerifiedSpecialist')->nullable();
            $table->string('PriorityDoctorCode', 100)->nullable();
            $table->text('PriorityDoctorDesc')->nullable();
            $table->text('PriorityDoctorSpecialist')->nullable();
            $table->string('ItemStatusCode', 100)->nullable();
            $table->text('ItemStatusDesc')->nullable();
            $table->string('Payor', 100)->nullable();
            $table->string('ItemCode', 100)->nullable();
            $table->text('ItemDescription')->nullable();
            $table->string('OperationCode', 100)->nullable();
            $table->text('OperationDesc')->nullable();
            $table->decimal('ItemPrice', 18, 2)->nullable();
            $table->decimal('QTY', 18, 2)->nullable();
            $table->decimal('TotalPrice', 18, 2)->nullable();
            $table->decimal('DiscountItem', 18, 2)->nullable();
            $table->decimal('AfterDisc', 18, 2)->nullable();
            $table->string('TypeofItem', 100)->nullable();
            $table->string('UOMCode', 100)->nullable();
            $table->string('UOMDesc', 100)->nullable();
            $table->string('TestOrderId', 100)->nullable();
            $table->string('AccessionNumber', 100)->nullable();
            $table->string('TestCode', 100)->nullable();
            $table->text('TestName')->nullable();
            $table->string('OrderSubCategoryCode', 100)->nullable();
            $table->text('OrderSubCategoryDesc')->nullable();
            $table->string('OrderCategoryCode', 100)->nullable();
            $table->text('OrderCategoryDesc')->nullable();
            $table->string('BillSubGroupCode', 100)->nullable();
            $table->text('BillSubGroupDesc')->nullable();
            $table->string('BillingGroupCode', 100)->nullable();
            $table->text('BillingGroupDesc')->nullable();
            $table->string('PackageCode', 100)->nullable();
            $table->text('PackageDescription')->nullable();
            $table->string('OrCatPackageCode', 100)->nullable();
            $table->text('OrCatPackageDesc')->nullable();
            $table->string('OrSubCatPackageCode', 100)->nullable();
            $table->text('OrSubCatPackageDesc')->nullable();
            $table->string('DfrCode', 100)->nullable();
            $table->string('RowIdAppt', 100)->nullable();
            $table->string('StatusAppt', 100)->nullable();
            $table->dateTime('OperationDate')->nullable();
            $table->dateTime('OrderDate')->nullable();
            $table->string('InvoiceNumber', 100)->nullable();
            $table->decimal('TotalPatient', 18, 2)->nullable();
            $table->decimal('TotalInsurance', 18, 2)->nullable();
            $table->string('BatchNo', 100)->nullable();
            $table->date('BatchDate')->nullable();
            $table->dateTime('DischargeDate')->nullable();
            $table->date('PrintedInvoiceDate')->nullable();
            $table->time('PrintedInvoiceTime')->nullable();
            $table->date('InvoiceCancelledDate')->nullable();
            $table->time('InvoiceCancelledTime')->nullable();
            $table->string('LocationOparation', 100)->nullable();
            $table->string('OperationNumber', 100)->nullable();
            $table->string('StatusOper', 100)->nullable();
            $table->string('AnasthesiaNumber', 100)->nullable();
            $table->string('StatusAnaes', 100)->nullable();
            $table->date('OperationStartDate')->nullable();
            $table->time('OperationStartTime')->nullable();
            $table->date('OperationEndDate')->nullable();
            $table->time('OperationEndTime')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctors_fee');
    }
};
