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
        Schema::table('tcmon_arc_itm_mast', function (Blueprint $table) {
            $table->renameColumn('TypeofItem', 'TypeofItemCode');
            $table->string('TypeofItemDesc')->nullable()->after('TypeofItem');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tcmon_arc_itm_mast', function (Blueprint $table) {
            $table->dropColumn('TypeofItemDesc');
            $table->renameColumn('TypeofItemCode', 'TypeofItem');
        });
    }
};
