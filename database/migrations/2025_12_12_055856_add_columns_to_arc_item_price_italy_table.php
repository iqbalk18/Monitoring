<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tcmon_arc_item_price_italy', function (Blueprint $table) {
            if (!Schema::hasColumn('tcmon_arc_item_price_italy', 'ITP_ARCIM_Code')) {
                $table->string('ITP_ARCIM_Code')->nullable()->after('id');
            }
            if (!Schema::hasColumn('tcmon_arc_item_price_italy', 'ITP_ARCIM_Desc')) {
                $table->string('ITP_ARCIM_Desc')->nullable()->after('ITP_ARCIM_Code');
            }
            if (!Schema::hasColumn('tcmon_arc_item_price_italy', 'ITP_DateFrom')) {
                $table->date('ITP_DateFrom')->nullable()->after('ITP_ARCIM_Desc');
            }
            if (!Schema::hasColumn('tcmon_arc_item_price_italy', 'ITP_DateTo')) {
                $table->date('ITP_DateTo')->nullable()->after('ITP_DateFrom');
            }
            if (!Schema::hasColumn('tcmon_arc_item_price_italy', 'ITP_TAR_Code')) {
                $table->string('ITP_TAR_Code')->nullable()->after('ITP_DateTo');
            }
            if (!Schema::hasColumn('tcmon_arc_item_price_italy', 'ITP_TAR_Desc')) {
                $table->string('ITP_TAR_Desc')->nullable()->after('ITP_TAR_Code');
            }
            if (!Schema::hasColumn('tcmon_arc_item_price_italy', 'ITP_Price')) {
                $table->decimal('ITP_Price', 15, 2)->nullable()->after('ITP_TAR_Desc');
            }
            if (!Schema::hasColumn('tcmon_arc_item_price_italy', 'ITP_CTCUR_Code')) {
                $table->string('ITP_CTCUR_Code')->nullable()->after('ITP_Price');
            }
            if (!Schema::hasColumn('tcmon_arc_item_price_italy', 'ITP_CTCUR_Desc')) {
                $table->string('ITP_CTCUR_Desc')->nullable()->after('ITP_CTCUR_Code');
            }
            if (!Schema::hasColumn('tcmon_arc_item_price_italy', 'ITP_ROOMT_Code')) {
                $table->string('ITP_ROOMT_Code')->nullable()->after('ITP_CTCUR_Desc');
            }
            if (!Schema::hasColumn('tcmon_arc_item_price_italy', 'ITP_ROOMT_Desc')) {
                $table->string('ITP_ROOMT_Desc')->nullable()->after('ITP_ROOMT_Code');
            }
            if (!Schema::hasColumn('tcmon_arc_item_price_italy', 'ITP_HOSP_Code')) {
                $table->string('ITP_HOSP_Code')->nullable()->after('ITP_ROOMT_Desc');
            }
            if (!Schema::hasColumn('tcmon_arc_item_price_italy', 'ITP_HOSP_Desc')) {
                $table->string('ITP_HOSP_Desc')->nullable()->after('ITP_HOSP_Code');
            }
            if (!Schema::hasColumn('tcmon_arc_item_price_italy', 'ITP_Rank')) {
                $table->string('ITP_Rank')->nullable()->after('ITP_HOSP_Desc');
            }
            if (!Schema::hasColumn('tcmon_arc_item_price_italy', 'ITP_EpisodeType')) {
                $table->string('ITP_EpisodeType')->nullable()->after('ITP_Rank');
            }
        });
    }

    public function down(): void
    {
        if (Schema::hasTable('tcmon_arc_item_price_italy')) {
            Schema::table('tcmon_arc_item_price_italy', function (Blueprint $table) {
                $table->dropColumn([
                    'ITP_ARCIM_Code',
                    'ITP_ARCIM_Desc',
                    'ITP_DateFrom',
                    'ITP_DateTo',
                    'ITP_TAR_Code',
                    'ITP_TAR_Desc',
                    'ITP_Price',
                    'ITP_CTCUR_Code',
                    'ITP_CTCUR_Desc',
                    'ITP_ROOMT_Code',
                    'ITP_ROOMT_Desc',
                    'ITP_HOSP_Code',
                    'ITP_HOSP_Desc',
                    'ITP_Rank',
                    'ITP_EpisodeType',
                ]);
            });
        }
    }
};
