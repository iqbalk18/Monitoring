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
        Schema::table('tcmon_arc_item_price_italy', function (Blueprint $table) {
            if (!Schema::hasColumn('tcmon_arc_item_price_italy', 'hna')) {
                $table->decimal('hna', 15, 2)->nullable()->after('ITP_Price');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tcmon_arc_item_price_italy', function (Blueprint $table) {
            if (Schema::hasColumn('tcmon_arc_item_price_italy', 'hna')) {
                $table->dropColumn('hna');
            }
        });
    }
};
