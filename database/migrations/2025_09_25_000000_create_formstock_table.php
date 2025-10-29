<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('formstock', function (Blueprint $table) {
            $table->id();
            $table->string('materialDocument')->nullable();
            $table->string('materialDocumentYear')->nullable();
            $table->string('plant')->nullable();
            $table->date('documentDate')->nullable();
            $table->date('postingDate')->nullable();
            $table->string('goodMovementText')->nullable();
            $table->string('vendor')->nullable();
            $table->string('purchaseOrder')->nullable();
            $table->string('reservation')->nullable();
            $table->string('outboundDelivery')->nullable();
            $table->date('sapTransactionDate')->nullable();
            $table->time('sapTransactionTime')->nullable();
            $table->string('user')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('formstock');
    }
};
