<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('work_log_templates', function (Blueprint $table) {
            // Make log_date NOT NULL
            $table->date('log_date')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('work_log_templates', function (Blueprint $table) {
            // Revert to nullable
            $table->date('log_date')->nullable()->change();
        });
    }
};
