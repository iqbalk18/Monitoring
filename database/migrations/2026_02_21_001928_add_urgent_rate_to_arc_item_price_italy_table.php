<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('tcmon_arc_item_price_italy', function (Blueprint $table) {
            $table->decimal('ITP_UrgentRate', 15, 2)->nullable()->after('ITP_Price');
        });
    }

    public function down(): void
    {
        Schema::table('tcmon_arc_item_price_italy', function (Blueprint $table) {
            $table->dropColumn('ITP_UrgentRate');
        });
    }
};
