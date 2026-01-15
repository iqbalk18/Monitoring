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
        Schema::table('price_submissions', function (Blueprint $table) {
            $table->decimal('hna', 15, 2)->nullable()->after('ITP_Price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('price_submissions', function (Blueprint $table) {
            $table->dropColumn('hna');
        });
    }
};
