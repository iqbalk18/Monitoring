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
        Schema::table('tcmon_price_submissions', function (Blueprint $table) {
            $table->string('TypeofItemCode')->nullable()->after('ITP_Rank');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tcmon_price_submissions', function (Blueprint $table) {
            $table->dropColumn('TypeofItemCode');
        });
    }
};
