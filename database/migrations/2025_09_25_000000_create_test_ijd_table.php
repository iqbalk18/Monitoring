<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestIjdTable extends Migration
{
    public function up(): void
    {
        Schema::create('ijd', function (Blueprint $table) {
            $table->id();

            $table->date('AdmDate')->nullable();
            $table->string('URN', 50)->nullable();
            $table->string('FirstName', 100)->nullable();
            $table->string('LastName', 100)->nullable();
            $table->string('EpisodeNo', 50)->nullable();
            $table->string('EpisodeType', 50)->nullable();
            $table->string('Nationality', 50)->nullable();

            $table->string('EpisodeDoctorCode', 50)->nullable();
            $table->string('EpisodeDoctorDesc', 150)->nullable();
            $table->string('SpecialistTypeEpisode', 50)->nullable();
            $table->string('EpisodeDoctorspecialist', 100)->nullable();

            $table->string('OrderDoctorCode', 50)->nullable();
            $table->string('OrderDoctorDesc', 150)->nullable();
            $table->string('SpecialistTypeOrder', 50)->nullable();
            $table->string('OrderDoctorspecialist', 100)->nullable();

            $table->string('ExecutedDoctorCode', 50)->nullable();
            $table->string('ExecutedDoctorDesc', 150)->nullable();
            $table->string('SpecialistTypeExecuted', 50)->nullable();
            $table->string('ExecutedDoctorspecialist', 100)->nullable();

            $table->string('AuthoriseDoctorCode', 50)->nullable();
            $table->string('AuthoriseDoctorDesc', 150)->nullable();
            $table->string('SpecialistTypeAuthorise', 50)->nullable();
            $table->string('AuthoriseDoctorSpecialist', 100)->nullable();

            $table->string('ConsultDoctorCode', 50)->nullable();
            $table->string('ConsultDoctorDesc', 150)->nullable();
            $table->string('SpecialistTypeConsult', 50)->nullable();
            $table->string('ConsultDoctorSpecialist', 100)->nullable();

            $table->string('DoctorSurgeonCode', 50)->nullable();
            $table->string('DoctorSurgeryDesc', 150)->nullable();
            $table->string('SpecialistType', 50)->nullable();
            $table->string('DoctorSurgerySpecialist', 100)->nullable();

            $table->string('DoctorAnaesCode', 50)->nullable();
            $table->string('DoctorAnaesDesc', 150)->nullable();
            $table->string('DoctorAnaesSpecialist', 100)->nullable();

            $table->string('ItemStatusCode', 20)->nullable();
            $table->string('ItemStatusDesc', 100)->nullable();
            $table->string('Payor', 50)->nullable();

            $table->string('ItemCode', 50)->nullable();
            $table->string('ItemDescription', 255)->nullable();

            $table->string('OperationCode', 50)->nullable();
            $table->string('OperationDesc', 255)->nullable();

            $table->decimal('ItemPrice', 15, 2)->nullable();
            $table->integer('QTY')->nullable();
            $table->decimal('TotalPrice', 15, 2)->nullable();
            $table->decimal('DiscountItem', 15, 2)->nullable();

            $table->string('TypeofItem', 50)->nullable();

            $table->string('OrderSubCategoryCode', 50)->nullable();
            $table->string('OrderSubCategoryDesc', 100)->nullable();
            $table->string('OrderCategoryCode', 50)->nullable();
            $table->string('OrderCategoryDesc', 100)->nullable();

            $table->string('BillSubGroupCode', 50)->nullable();
            $table->string('BillSubGroupDesc', 100)->nullable();
            $table->string('BillingGroupCode', 50)->nullable();
            $table->string('BillingGroupDesc', 100)->nullable();

            $table->string('PackageCode', 50)->nullable();
            $table->string('PackageDescription', 255)->nullable();
            $table->string('OrCatPackageCode', 50)->nullable();
            $table->string('OrCatPackageDesc', 100)->nullable();
            $table->string('OrSubCatPackageCode', 50)->nullable();
            $table->string('OrSubCatPackageDesc', 100)->nullable();

            $table->string('DfrCode', 50)->nullable();
            $table->string('RowIdAppt', 50)->nullable();
            $table->string('StatusAppt', 50)->nullable();

            $table->date('OperationDate')->nullable();
            $table->date('OrderDate')->nullable();
            $table->string('InvoiceNumber', 50)->nullable();

            $table->decimal('TotalPatient', 15, 2)->nullable();
            $table->decimal('TotalInsurance', 15, 2)->nullable();

            $table->date('DischargeDate')->nullable();
            $table->date('PrintedInvoiceDate')->nullable();
            $table->time('PrintedInvoiceTime')->nullable();
            $table->date('InvoiceCancelledDate')->nullable();
            $table->time('InvoiceCancelledTime')->nullable();

            $table->string('LocationOparation', 100)->nullable();
            $table->string('OperationNumber', 50)->nullable();
            $table->string('StatusOper', 50)->nullable();

            $table->string('AnasthesiaNumber', 50)->nullable();
            $table->string('StatusAnaes', 50)->nullable();

            $table->date('OperationStartDate')->nullable();
            $table->time('OperationStartTime')->nullable();
            $table->date('OperationEndDate')->nullable();
            $table->time('OperationEndTime')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ijd');
    }
}
