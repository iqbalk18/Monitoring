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
        Schema::create('tcmon_stocksaptc', function (Blueprint $table) {
            $table->id();
            $table->date('Period_DateTime')->nullable();
            $table->string('Combine_Code')->nullable()->index();
            $table->string('Material_Desc')->nullable();
            $table->string('Material_Code')->nullable();
            $table->string('Plant')->nullable();
            $table->string('Storage_Loc')->nullable();
            $table->string('Batch_No')->nullable();
            $table->string('BU_Code')->nullable();
            $table->decimal('QTY_SAP', 18, 2)->default(0);
            $table->decimal('QTY_TC', 18, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tcmon_stocksaptc');
    }
};
