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
        Schema::create('payslips', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('fortnight_id');
            $table->unsignedBigInteger('payroll_id');
            $table->unsignedBigInteger('business_id');
            $table->decimal('regular', 10, 2);
            $table->decimal('overtime', 10, 2);
            $table->decimal('sunday_ot', 10, 2);
            $table->decimal('holiday_ot', 10, 2);
            $table->decimal('plp_alp_fp', 10, 2);
            $table->decimal('other', 10, 2);
            $table->decimal('fn_tax', 10, 2);
            $table->decimal('npf', 10, 2);
            $table->decimal('ncsl', 10, 2);
            $table->decimal('cash_adv', 10, 2);
            $table->boolean('is_approved')->default(0);
            $table
                ->foreign('employee_id')
                ->references('id')
                ->on('employees')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
            $table
                ->foreign('fortnight_id')
                ->references('id')
                ->on('fortnights')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
            $table
                ->foreign('payroll_id')
                ->references('id')
                ->on('payrolls')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
            $table
                ->foreign('business_id')
                ->references('id')
                ->on('businesses')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payslips');
    }
};
