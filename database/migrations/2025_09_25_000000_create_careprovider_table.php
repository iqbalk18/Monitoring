<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('careprovider', function (Blueprint $table) {
            $table->id();
            $table->string('CTPCP_Code')->nullable();
            $table->string('CTPCP_Desc')->nullable();
            $table->string('CTCPT_Code')->nullable();
            $table->string('CTCPT_Desc')->nullable();
            $table->string('CTPCP_Stname')->nullable();
            $table->string('CTPCP_Level')->nullable();
            $table->string('CTSPC_Spec_Code')->nullable();
            $table->string('CTSPC_Spec_Desc')->nullable();
            $table->string('CTSPC_SubSpec_Code')->nullable();
            $table->string('CTSPC_SubSpec_Desc')->nullable();
            $table->string('CTPCP_TelH')->nullable();
            $table->string('CTPCP_PagerNo')->nullable();
            $table->string('CTPCP_SpecialistYN')->nullable();
            $table->date('CTPCP_DateActiveFrom')->nullable();
            $table->date('CTPCP_DateActiveTo')->nullable();
            $table->string('CTPCP_PrescriberNumber')->nullable();
            $table->string('RU_Code')->nullable();
            $table->string('RU_Desc')->nullable();
            $table->string('CTLOC_Code')->nullable();
            $table->string('CTLOC_Desc')->nullable();
            $table->string('CTPCP_Title')->nullable();
            $table->string('CTPCP_DOB')->nullable();
            $table->string('CTPCP_MobilePhone')->nullable();
            $table->string('CTPCP_Fax')->nullable();
            $table->string('CTPCP_Email')->nullable();
            $table->string('CTPCP_FirstName')->nullable();
            $table->string('TTL_Code')->nullable();
            $table->string('TTL_Desc')->nullable();
            $table->string('CTPCP_Surname')->nullable();
            $table->string('CTPCP_ConfidentialFax')->nullable();
            $table->string('CTPCP_CodeTranslated')->nullable();
            $table->string('CTPCP_DescTranslated')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('formstock');
    }
};
