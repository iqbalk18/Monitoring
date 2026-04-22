<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tcmon_ar_tracking', function (Blueprint $table) {
            $table->date('cancelled_date')->nullable()->after('paid_on');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tcmon_ar_tracking', function (Blueprint $table) {
            $table->dropColumn('cancelled_date');
        });
    }
};
