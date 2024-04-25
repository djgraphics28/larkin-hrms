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
        Schema::create('employee_hours', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fortnight_id');
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('salary_id');
            $table->decimal('regular_hr', 10, 2);
            $table->decimal('overtime_hr', 10, 2);
            $table->decimal('sunday_ot_hr', 10, 2);
            $table->decimal('holiday_ot_hr', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_hours');
    }
};
