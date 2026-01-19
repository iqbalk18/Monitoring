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
        Schema::table('ijd', function (Blueprint $table) {
            $table->string('ExecutedDoctorCode')->nullable()->after('OrderDoctorspecialist');
            $table->string('ExecutedDoctorDesc')->nullable()->after('ExecutedDoctorCode');
            $table->string('SpecialistTypeExecuted')->nullable()->after('ExecutedDoctorDesc');
            $table->string('ExecutedDoctorspecialist')->nullable()->after('SpecialistTypeExecuted');

            // Rename SpecialistTypeSurgery to SpecialistType if it exists, otherwise add SpecialistType
            if (Schema::hasColumn('ijd', 'SpecialistTypeSurgery')) {
                $table->renameColumn('SpecialistTypeSurgery', 'SpecialistType');
            } else {
                $table->string('SpecialistType')->nullable()->after('DoctorSurgeryDesc');
            }

            $table->string('OrCatPackageCode')->nullable()->after('PackageDescription');
            $table->string('OrCatPackageDesc')->nullable()->after('OrCatPackageCode');
            $table->string('OrSubCatPackageCode')->nullable()->after('OrCatPackageDesc');
            $table->string('OrSubCatPackageDesc')->nullable()->after('OrSubCatPackageCode');

            $table->time('PrintedInvoiceTime')->nullable()->after('PrintedInvoiceDate');
            $table->time('InvoiceCancelledTime')->nullable()->after('InvoiceCancelledDate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ijd', function (Blueprint $table) {
            $table->dropColumn([
                'ExecutedDoctorCode',
                'ExecutedDoctorDesc',
                'SpecialistTypeExecuted',
                'ExecutedDoctorspecialist',
                'OrCatPackageCode',
                'OrCatPackageDesc',
                'OrSubCatPackageCode',
                'OrSubCatPackageDesc',
                'PrintedInvoiceTime',
                'InvoiceCancelledTime'
            ]);

            if (Schema::hasColumn('ijd', 'SpecialistType')) {
                $table->renameColumn('SpecialistType', 'SpecialistTypeSurgery');
            }
        });
    }
};
