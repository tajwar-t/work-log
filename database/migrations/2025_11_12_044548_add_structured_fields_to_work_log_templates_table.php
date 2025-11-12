<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('work_log_templates', function (Blueprint $table) {
            $table->text('day_start_last_day')->nullable()->after('content');
            $table->text('day_start_today')->nullable()->after('day_start_last_day');
            $table->text('day_end_today')->nullable()->after('day_start_today');
            $table->text('day_end_tomorrow')->nullable()->after('day_end_today');
        });
    }

    public function down()
    {
        Schema::table('work_log_templates', function (Blueprint $table) {
            $table->dropColumn(['day_start_last_day','day_start_today','day_end_today','day_end_tomorrow']);
        });
    }
};
