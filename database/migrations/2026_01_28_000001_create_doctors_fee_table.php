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
            $table->string('URN', 50)->nullable();
            $table->string('FirstName', 100)->nullable();
            $table->string('LastName', 100)->nullable();
            $table->string('EpisodeNo', 50)->nullable();
            $table->string('EpisodeType', 50)->nullable();
            $table->string('Nationality', 50)->nullable();

            // Episode Doctor
            $table->string('EpisodeDoctorCode', 50)->nullable();
            $table->string('EpisodeDoctorDesc', 150)->nullable();
            $table->string('SpecialistTypeEpisode', 50)->nullable();
            $table->string('EpisodeDoctorspecialist', 100)->nullable();

            // Order Doctor
            $table->string('OrderDoctorCode', 50)->nullable();
            $table->string('OrderDoctorDesc', 150)->nullable();
            $table->string('SpecialistTypeOrder', 50)->nullable();
            $table->string('OrderDoctorspecialist', 100)->nullable();

            // Executed Doctor
            $table->string('ExecutedDoctorCode', 50)->nullable();
            $table->string('ExecutedDoctorDesc', 150)->nullable();
            $table->string('SpecialistTypeExecuted', 50)->nullable();
            $table->string('ExecutedDoctorspecialist', 100)->nullable();

            // Authorise Doctor
            $table->string('AuthoriseDoctorCode', 50)->nullable();
            $table->string('AuthoriseDoctorDesc', 150)->nullable();
            $table->string('SpecialistTypeAuthorise', 50)->nullable();
            $table->string('AuthoriseDoctorSpecialist', 100)->nullable();

            // Consult Doctor
            $table->string('ConsultDoctorCode', 50)->nullable();
            $table->string('ConsultDoctorDesc', 150)->nullable();
            $table->string('SpecialistTypeConsult', 50)->nullable();
            $table->string('ConsultDoctorSpecialist', 100)->nullable();

            // Surgeon
            $table->string('DoctorSurgeonCode', 50)->nullable();
            $table->string('DoctorSurgeryDesc', 150)->nullable();
            $table->string('SpecialistType', 50)->nullable();
            $table->string('DoctorSurgerySpecialist', 100)->nullable();

            // Anaesthetist
            $table->string('DoctorAnaesCode', 50)->nullable();
            $table->string('DoctorAnaesDesc', 150)->nullable();
            $table->string('DoctorAnaesSpecialist', 100)->nullable();

            // Item Info
            $table->string('ItemStatusCode', 50)->nullable();
            $table->string('ItemStatusDesc', 150)->nullable();
            $table->string('Payor', 100)->nullable();
            $table->string('ItemCode', 50)->nullable();
            $table->string('ItemDescription', 200)->nullable();
            $table->string('OperationCode', 50)->nullable();
            $table->string('OperationDesc', 200)->nullable();

            $table->decimal('ItemPrice', 18, 2)->nullable();
            $table->decimal('QTY', 18, 2)->nullable();
            $table->decimal('TotalPrice', 18, 2)->nullable();
            $table->decimal('DiscountItem', 18, 2)->nullable();
            $table->decimal('AfterDiscount', 18, 2)->nullable();

            $table->string('TypeofItem', 50)->nullable();
            $table->string('UOMCode', 50)->nullable();
            $table->string('UOMDesc', 100)->nullable();

            // Test/Order Info
            $table->string('TestOrderId', 50)->nullable();
            $table->string('AccessionNumber', 50)->nullable();
            $table->string('TestCode', 50)->nullable();
            $table->string('TestName', 150)->nullable();

            $table->string('OrderSubCategoryCode', 50)->nullable();
            $table->string('OrderSubCategoryDesc', 150)->nullable();
            $table->string('OrderCategoryCode', 50)->nullable();
            $table->string('OrderCategoryDesc', 150)->nullable();

            $table->string('BillSubGroupCode', 50)->nullable();
            $table->string('BillSubGroupDesc', 150)->nullable();
            $table->string('BillingGroupCode', 50)->nullable();
            $table->string('BillingGroupDesc', 150)->nullable();

            $table->string('PackageCode', 50)->nullable();
            $table->string('PackageDescription', 150)->nullable();
            $table->string('OrCatPackageCode', 50)->nullable();
            $table->string('OrCatPackageDesc', 150)->nullable();
            $table->string('OrSubCatPackageCode', 50)->nullable();
            $table->string('OrSubCatPackageDesc', 150)->nullable();

            $table->string('DfrCode', 50)->nullable();
            $table->string('RowIdAppt', 50)->nullable();
            $table->string('StatusAppt', 50)->nullable();

            // Dates & Invoice
            $table->date('OperationDate')->nullable();
            $table->date('OrderDate')->nullable();
            $table->string('InvoiceNumber', 50)->nullable();
            $table->decimal('TotalPatient', 18, 2)->nullable();
            $table->decimal('TotalInsurance', 18, 2)->nullable();

            $table->string('BatchNo', 50)->nullable();
            $table->date('BatchDate')->nullable();
            $table->date('DischargeDate')->nullable();

            $table->date('PrintedInvoiceDate')->nullable();
            $table->time('PrintedInvoiceTime')->nullable();

            $table->date('InvoiceCancelledDate')->nullable();
            $table->time('InvoiceCancelledTime')->nullable();

            // Operation Details
            $table->string('LocationOparation', 100)->nullable();
            $table->string('OperationNumber', 50)->nullable();
            $table->string('StatusOper', 50)->nullable();
            $table->string('AnasthesiaNumber', 50)->nullable();
            $table->string('StatusAnaes', 50)->nullable();

            $table->date('OperationStartDate')->nullable();
            $table->time('OperationStartTime')->nullable();
            $table->date('OperationEndDate')->nullable();
            $table->time('OperationEndTime')->nullable();

            // Reporting Doctor
            $table->string('ReportingDoctorCode', 50)->nullable();
            $table->string('ReportingDoctorDesc', 150)->nullable();
            $table->string('SpecialistTypeReporting', 50)->nullable();
            $table->string('DoctorReportingSpecialist', 100)->nullable();

            // Additional Doctor
            $table->string('AdditionalDoctorCode', 50)->nullable();
            $table->string('AdditionalDoctorDesc', 150)->nullable();
            $table->string('SpecialistTypeAdditional', 50)->nullable();
            $table->string('DoctorAdditoonalSpecialist', 100)->nullable();

            // Verified Doctor
            $table->string('VerifiedDoctorCode', 50)->nullable();
            $table->string('VerifiedDoctorDesc', 150)->nullable();
            $table->string('SpecialistTypeVerified', 50)->nullable();
            $table->string('DoctorVerifiedSpecialist', 100)->nullable();

            // Priority & Results
            $table->string('PriorityDoctorCode', 50)->nullable();
            $table->string('PriorityDoctorDesc', 150)->nullable();
            $table->string('PriorityDoctorSpecialist', 100)->nullable();
            $table->decimal('PercentDoctor', 5, 2)->nullable();
            $table->decimal('ResultDoctor', 18, 2)->nullable();
            $table->decimal('PercentDoctorAnaesthesia', 5, 2)->nullable();
            $table->decimal('ResultDoctorAneasthesia', 18, 2)->nullable();

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
