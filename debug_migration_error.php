<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    Schema::create('doctors_fee_debug', function (Blueprint $table) {
        $table->id();

        // Dates
        $table->date('AdmDate')->nullable();

        // Strings - Patient & Episode Info
        $table->string('URN')->nullable();
        $table->string('FirstName')->nullable();
        $table->string('LastName')->nullable();
        $table->string('EpisodeNo')->nullable();
        $table->string('EpisodeType')->nullable();
        $table->string('Nationality')->nullable();

        // Doctors Info
        $table->string('EpisodeDoctorCode')->nullable();
        $table->string('EpisodeDoctorDesc')->nullable();
        $table->string('SpecialistTypeEpisode')->nullable();
        $table->string('EpisodeDoctorspecialist')->nullable();

        $table->string('OrderDoctorCode')->nullable();
        $table->string('OrderDoctorDesc')->nullable();
        $table->string('SpecialistTypeOrder')->nullable();
        $table->string('OrderDoctorspecialist')->nullable();

        $table->string('ExecutedDoctorCode')->nullable();
        $table->string('ExecutedDoctorDesc')->nullable();
        $table->string('SpecialistTypeExecuted')->nullable();
        $table->string('ExecutedDoctorspecialist')->nullable();

        $table->string('AuthoriseDoctorCode')->nullable();
        $table->string('AuthoriseDoctorDesc')->nullable();
        $table->string('SpecialistTypeAuthorise')->nullable();
        $table->string('AuthoriseDoctorSpecialist')->nullable();

        $table->string('ConsultDoctorCode')->nullable();
        $table->string('ConsultDoctorDesc')->nullable();
        $table->string('SpecialistTypeConsult')->nullable();
        $table->string('ConsultDoctorSpecialist')->nullable();

        $table->string('DoctorSurgeonCode')->nullable();
        $table->string('DoctorSurgeryDesc')->nullable();
        $table->string('SpecialistType')->nullable(); // Renamed from SpecialistTypeSurgery
        $table->string('DoctorSurgerySpecialist')->nullable();

        $table->string('PriorityDoctorCode')->nullable();
        $table->string('PriorityDoctorDesc')->nullable();
        $table->string('PriorityDoctorSpecialist')->nullable();

        $table->string('DoctorAnaesCode')->nullable();
        $table->string('DoctorAnaesDesc')->nullable();
        $table->string('DoctorAnaesSpecialist')->nullable();

        // Item Info
        $table->string('ItemStatusCode')->nullable();
        $table->string('ItemStatusDesc')->nullable();
        $table->string('Payor')->nullable();
        $table->string('ItemCode')->nullable();
        $table->text('ItemDescription')->nullable();
        $table->string('OperationCode')->nullable();
        $table->text('OperationDesc')->nullable();

        // Financials
        $table->decimal('ItemPrice', 15, 2)->nullable();
        $table->decimal('QTY', 15, 2)->nullable();
        $table->decimal('TotalPrice', 15, 2)->nullable();
        $table->decimal('DiscountItem', 15, 2)->nullable();
        $table->decimal('AfterDiscount', 15, 2)->nullable();

        // Classification
        $table->string('TypeofItem')->nullable();
        $table->string('OrderSubCategoryCode')->nullable();
        $table->string('OrderSubCategoryDesc')->nullable();
        $table->string('OrderCategoryCode')->nullable();
        $table->string('OrderCategoryDesc')->nullable();
        $table->string('BillSubGroupCode')->nullable();
        $table->string('BillSubGroupDesc')->nullable();
        $table->string('BillingGroupCode')->nullable();
        $table->string('BillingGroupDesc')->nullable();

        // Package Info
        $table->string('PackageCode')->nullable();
        $table->text('PackageDescription')->nullable();
        $table->string('OrCatPackageCode')->nullable();
        $table->string('OrCatPackageDesc')->nullable();
        $table->string('OrSubCatPackageCode')->nullable();
        $table->string('OrSubCatPackageDesc')->nullable();

        // Other System Codes
        $table->string('DfrCode')->nullable();
        $table->string('RowIdAppt')->nullable();
        $table->string('StatusAppt')->nullable();

        // More Dates and Invoice
        $table->date('OperationDate')->nullable();
        $table->date('OrderDate')->nullable();
        $table->string('InvoiceNumber')->nullable();
        $table->decimal('TotalPatient', 15, 2)->nullable();
        $table->decimal('TotalInsurance', 15, 2)->nullable();

        $table->date('DischargeDate')->nullable();
        $table->date('PrintedInvoiceDate')->nullable();
        $table->time('PrintedInvoiceTime')->nullable();
        $table->date('InvoiceCancelledDate')->nullable();
        $table->time('InvoiceCancelledTime')->nullable();

        // New Operation columns
        $table->string('LocationOparation')->nullable();
        $table->string('OperationNumber')->nullable();
        $table->string('StatusOper')->nullable();
        $table->string('AnasthesiaNumber')->nullable();
        $table->string('StatusAnaes')->nullable();
        $table->date('OperationStartDate')->nullable();
        $table->time('OperationStartTime')->nullable();
        $table->date('OperationEndDate')->nullable();
        $table->time('OperationEndTime')->nullable();

        // Results
        $table->decimal('PercentDoctor', 5, 2)->nullable();
        $table->decimal('PercentDoctorAnesthesia', 5, 2)->nullable();
        $table->decimal('ResultDoctor', 15, 2)->nullable();
        $table->decimal('ResultDoctorAnesthesia', 15, 2)->nullable();

        $table->timestamps();
    });
    echo "Migration successful!\n";
    Schema::dropIfExists('doctors_fee_debug');
} catch (\Exception $e) {
    echo "Migration failed: " . $e->getMessage() . "\n";
}
