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
        Schema::create('leave_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('status',['Approved','Rejected','Cancelled','Pending'])->default('Pending');
            $table->date('date_from');
            $table->date('date_to');
            $table->boolean('is_half_day')->default(false);
            $table->enum('choosen_half', ['first_half', 'second_half'])->nullable();
            $table->integer('with_pay_number_of_days');
            $table->integer('without_pay_number_of_days');
            $table->string('reason');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->date('date_approved')->nullable();
            $table->unsignedBigInteger('leave_type_id');
            $table->unsignedBigInteger('employee_id');
            $table
                ->foreign('employee_id')
                ->references('id')
                ->on('employees')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
            $table
                ->foreign('leave_type_id')
                ->references('id')
                ->on('leave_types')
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
        Schema::dropIfExists('leave_requests');
    }
};
