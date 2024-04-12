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
        Schema::create('employees', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('employee_number', 30)->unique();
            $table->string('first_name', 100);
            $table->string('middle_name', 100)->nullable();
            $table->string('last_name', 100);
            $table->string('ext_name', 10)->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('phone')->nullable();
            $table->enum('marital_status',['Single', 'Married', 'Divorced', 'Widowed'])->nullable();
            $table->date('birth_date')->nullable();
            $table->date('joining_date')->nullable();
            $table->date('end_date')->nullable();
            $table->date('deployment_date_home_country')->nullable();
            $table->enum('label', ['National','Expatriate']);
            $table->enum('gender', ['Male','Female']);
            $table->string('image')->nullable();
            $table->unsignedBigInteger('designation_id');
            $table->unsignedBigInteger('employee_status_id');
            $table->unsignedBigInteger('business_id');
            $table->unsignedBigInteger('department_id');
            $table->unsignedBigInteger('workshift_id');
            $table
                ->foreign('designation_id')
                ->references('id')
                ->on('designations')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table
                ->foreign('employee_status_id')
                ->references('id')
                ->on('employee_statuses')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table
                ->foreign('department_id')
                ->references('id')
                ->on('departments')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table
                ->foreign('business_id')
                ->references('id')
                ->on('businesses')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
            $table
                ->foreign('workshift_id')
                ->references('id')
                ->on('workshifts')
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
        Schema::dropIfExists('employees');
    }
};
