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
            $table->string('materialDocument'); 
            $table->string('movementType');
            $table->string('specialStockIndicator')->nullable();
            $table->string('indicator');
            $table->string('material');
            $table->string('sloc');
            $table->string('batch');
            $table->date('expiredDate')->nullable();
            $table->string('expiredDateFreeText')->nullable();
            $table->string('qty');
            $table->string('uom');
            $table->string('qtySku');
            $table->string('uomSku');
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
