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
        Schema::create('tcmon_StockSAP', function (Blueprint $table) {
            $table->id();
            $table->string('Combine_Code')->nullable();
            $table->datetime('Period_DateTime')->nullable();
            $table->string('Material_Desc')->nullable();
            $table->string('Material_Code')->nullable();
            $table->string('Plant')->nullable();
            $table->string('Storage_Loc')->nullable();
            $table->string('Dfstor_loc_level')->nullable();
            $table->string('Batch_No')->nullable();
            $table->string('BU_Code')->nullable();
            $table->decimal('Qty', 15, 2)->nullable();
            $table->string('Stock_Segment')->nullable();
            $table->string('Currency')->nullable();
            $table->decimal('Value_Unrestricted', 15, 2)->nullable();
            $table->decimal('Transit_Transfer', 15, 2)->nullable();
            $table->decimal('Valin_Trans_Tfr', 15, 2)->nullable();
            $table->decimal('Quality_Inspection', 15, 2)->nullable();
            $table->decimal('Value_in_QualInsp', 15, 2)->nullable();
            $table->decimal('Restricted_UseStock', 15, 2)->nullable();
            $table->decimal('Value_Restricted', 15, 2)->nullable();
            $table->decimal('Blocked', 15, 2)->nullable();
            $table->decimal('Value_BlockedStock', 15, 2)->nullable();
            $table->decimal('Returns', 15, 2)->nullable();
            $table->decimal('Value_RetsBlocked', 15, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tcmon_StockSAP');
    }
};
