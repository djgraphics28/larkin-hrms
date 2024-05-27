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
        Schema::create('payrolls', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('payroll_code')->unique();
            $table->enum('status', ['Approved', 'Released', 'Rejected', 'Cancelled', 'For-Approval', 'On-Hold', 'Completed'])->default('For-Approval');
            $table->string('remarks')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->unsignedBigInteger('fortnight_id');
            $table->unsignedBigInteger('business_id');
            $table
                ->foreign('fortnight_id')
                ->references('id')
                ->on('fortnights')
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
        Schema::dropIfExists('payrolls');
    }
};
