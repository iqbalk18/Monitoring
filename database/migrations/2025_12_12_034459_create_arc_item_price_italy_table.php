<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('arc_item_price_italy', function (Blueprint $table) {
            $table->id();
            $table->string('ITP_ARCIM_Code')->nullable();
            $table->string('ITP_ARCIM_Desc')->nullable();
            $table->date('ITP_DateFrom')->nullable();
            $table->date('ITP_DateTo')->nullable();
            $table->string('ITP_TAR_Code')->nullable();
            $table->string('ITP_TAR_Desc')->nullable();
            $table->decimal('ITP_Price', 15, 2)->nullable();
            $table->string('ITP_CTCUR_Code')->nullable();
            $table->string('ITP_CTCUR_Desc')->nullable();
            $table->string('ITP_ROOMT_Code')->nullable();
            $table->string('ITP_ROOMT_Desc')->nullable();
            $table->string('ITP_HOSP_Code')->nullable();
            $table->string('ITP_HOSP_Desc')->nullable();
            $table->string('ITP_Rank')->nullable();
            $table->string('ITP_EpisodeType')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('arc_item_price_italy');
    }
};
