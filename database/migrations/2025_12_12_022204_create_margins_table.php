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
        Schema::create('margins', function (Blueprint $table) {
            $table->id();
            $table->string('TypeofItemCode')->nullable();
            $table->string('TypeofItemDesc')->nullable();
            $table->string('Margin')->nullable();
            $table->string('ARCIM_ServMateria')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('margins');
    }
};
