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
        Schema::create('leave_credits', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('credit');
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('leave_type_id');
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
        Schema::dropIfExists('leave_credits');
    }
};
