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
        Schema::table('tcmon_margins', function (Blueprint $table) {
            $table->date('DateFrom')->nullable();
            $table->date('DateTo')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tcmon_margins', function (Blueprint $table) {
            $table->dropColumn(['DateFrom', 'DateTo']);
        });
    }
};
