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
        Schema::create('tcmon_arc_itm_mast', function (Blueprint $table) {
            $table->id();
            $table->string('ARCIM_Code')->nullable();
            $table->string('ARCIM_Desc')->nullable();
            $table->string('ARCIM_ServMaterial')->nullable();
            $table->string('ARCIC_Code')->nullable();
            $table->string('ARCIC_Desc')->nullable();
            $table->string('ORCAT_Code')->nullable();
            $table->string('ORCAT_Desc')->nullable();
            $table->string('ARCSG_Code')->nullable();
            $table->string('ARCSG_Desc')->nullable();
            $table->string('ARCBG_Code')->nullable();
            $table->string('ARCBG_Desc')->nullable();
            $table->string('ARCIM_OrderOnItsOwn')->nullable();
            $table->string('ARCIM_ReorderOnItsOwn')->nullable();
            $table->date('ARCIM_EffDate')->nullable();
            $table->date('ARCIM_EffDateTo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tcmon_arc_itm_mast');
    }
};
