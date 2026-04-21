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
        Schema::create('tcmon_ar_billing', function (Blueprint $table) {
            $table->id();

            $table->string('bat_number')->nullable();
            $table->date('bat_datecreated')->nullable();

            $table->string('invoiceno')->nullable();
            $table->timestamp('arpbl_dateprinted')->nullable();
            $table->timestamp('arpbl_datecancelled')->nullable();

            $table->string('flagcancel')->nullable();

            $table->timestamp('admdate')->nullable();
            $table->string('paadm_type')->nullable();

            $table->string('urn')->nullable();
            $table->string('episodeno')->nullable();

            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();

            $table->string('nationality')->nullable();
            $table->string('inst_desc')->nullable();

            $table->integer('totalpatient')->default(0);
            $table->integer('totalinsurance')->default(0);

            $table->decimal('beforediscount', 15, 2)->default(0);
            $table->decimal('afterdiscount', 15, 2)->default(0);
            $table->decimal('outstanding', 15, 2)->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tcmon_ar_billing');
    }
};
