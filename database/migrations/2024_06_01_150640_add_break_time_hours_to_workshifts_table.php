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
        Schema::table('workshifts', function (Blueprint $table) {
            $table->string('break_time_hours')->after('number_of_hours_fn')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('workshifts', function (Blueprint $table) {
            $table->dropColumn('break_time_hours');
        });
    }
};
