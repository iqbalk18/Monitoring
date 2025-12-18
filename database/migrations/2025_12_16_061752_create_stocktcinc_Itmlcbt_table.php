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
        Schema::create('tcmon_StockTCINC_ItmLcBt', function (Blueprint $table) {
            $table->id();
            $table->string('Combine_Code')->nullable();
            $table->datetime('Period_DateTime')->nullable();
            $table->string('INCLB_INCI_Code')->nullable();
            $table->string('INCLB_INCI_Desc')->nullable();
            $table->string('INCLB_INCIB_No')->nullable();
            $table->date('INCLB_INCIB_ExpDate')->nullable();
            $table->string('INCLB_CTLOC_Code')->nullable();
            $table->string('INCLB_CTLOC_Desc')->nullable();
            $table->decimal('INCLB_PhyQty', 15, 2)->nullable();
            $table->string('CTUOM_Code')->nullable();
            $table->string('CTUOM_Desc')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tcmon_StockTCINC_ItmLcBt');
    }
};
