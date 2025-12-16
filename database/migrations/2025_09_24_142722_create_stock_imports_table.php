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
        Schema::create('stock', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stocktcinc_itmlcbt_id')->nullable();
            $table->unsignedBigInteger('stocksap_id')->nullable();
            $table->string('Combine_Code')->nullable();
            $table->string('materialDocument'); 
            $table->string('movementType')->nullable();
            $table->string('specialStockIndicator')->nullable();
            $table->string('indicator');
            $table->string('material')->nullable();
            $table->string('sloc')->nullable();
            $table->string('batch')->nullable();
            $table->date('expiredDate')->nullable();
            $table->string('expiredDateFreeText')->nullable();
            $table->string('qty');
            $table->string('uom')->nullable();
            $table->string('qtySku');
            $table->string('uomSku')->nullable();
            $table->string('currency')->nullable();
            $table->string('poBasePricePerUnit')->nullable();
            $table->string('poDiscountPerUnit')->nullable();
            $table->string('amountInLocalCurrency')->nullable();
            $table->string('map')->nullable();
            $table->string('taxCode')->nullable();
            $table->string('taxRate')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock');
    }
};
