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
        Schema::table('price_submissions', function (Blueprint $table) {
            $table->string('submission_type')->default('ADD')->after('batch_id'); // ADD, EDIT
            $table->unsignedBigInteger('original_price_id')->nullable()->after('submission_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('price_submissions', function (Blueprint $table) {
            $table->dropColumn('submission_type');
            $table->dropColumn('original_price_id');
        });
    }
};
