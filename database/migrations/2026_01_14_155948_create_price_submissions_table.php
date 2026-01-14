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
        Schema::create('price_submissions', function (Blueprint $table) {
            $table->id();

            // Mirrored fields from tcmon_arc_item_price_italy
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

            // Approval Workflow fields
            $table->enum('status', ['PENDING', 'APPROVED', 'REJECTED'])->default('PENDING');
            $table->foreignId('submitted_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('rejection_reason')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('price_submissions');
    }
};
