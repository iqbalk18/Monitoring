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
        Schema::create('tcmon_ar_tracking', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no', 50)->nullable()->index();
            $table->string('status', 50)->default('BATCHING');
            $table->string('ref_no', 100)->nullable();
            $table->string('courier_via', 50)->nullable();
            $table->string('tracking_no', 100)->nullable();
            $table->date('sent_date')->nullable();
            $table->date('received_date')->nullable();
            $table->date('paid_on')->nullable();
            $table->integer('due_days')->default(0);
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tcmon_ar_tracking');
    }
};
