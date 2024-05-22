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
        Schema::create('loans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('status', ['Approved', 'Rejected', 'Cancelled', 'Pending', 'On-Hold'])->default('Pending');
            $table->string('reason');
            $table->decimal('amount', 16);
            $table->decimal('amount_to_be_deducted', 16);
            $table->integer('payable_for')->nullable(); //numbers of months to be paid
            $table->decimal('percentage_to_be_deducted', 2)->nullable(); //percentage to be deducted on the salary
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->date('date_requested');
            $table->date('date_approved')->nullable();
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('loan_type_id');
            $table->unsignedBigInteger('business_id');
            $table
                ->foreign('employee_id')
                ->references('id')
                ->on('employees')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
            $table
                ->foreign('loan_type_id')
                ->references('id')
                ->on('loan_types')
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
        Schema::dropIfExists('loans');
    }
};
