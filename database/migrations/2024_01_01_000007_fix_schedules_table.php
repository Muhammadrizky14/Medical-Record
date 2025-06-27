<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            // Pastikan kolom start_time dan end_time menggunakan tipe TIME
            $table->time('start_time')->change();
            $table->time('end_time')->change();
        });
    }

    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->datetime('start_time')->change();
            $table->datetime('end_time')->change();
        });
    }
};
