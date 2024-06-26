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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fortnight_id');
            $table->string('employee_number');
            $table->date('date');
            $table->string('time_in')->nullable();
            $table->string('time_out')->nullable();
            $table->string('time_in_2')->nullable();
            $table->string('time_out_2')->nullable();
            $table->boolean('is_break')->default(false);
            $table->integer('late_in_minutes')->default(0);
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            //if leave is approved, will save leave id
            $table->unsignedBigInteger('leave_id')->nullable();
            $table->enum('on_leave',['whole_day','first_half','second_half'])->nullable();

            $table
                ->foreign('fortnight_id')
                ->references('id')
                ->on('fortnights')
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
        Schema::dropIfExists('attendances');
    }
};
