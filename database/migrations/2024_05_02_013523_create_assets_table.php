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
        Schema::create('assets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('asset_code')->unique();
            $table->string('name');
            $table->text('note');
            $table->integer('quantity')->default(1);
            $table->date('date_received')->nullable();
            $table->date('date_returned')->nullable();
            $table->unsignedBigInteger('transfered_by')->nullable();
            $table->date('date_transfered')->nullable();
            $table->enum('is_working',['yes','no','maintenance'])->default('yes');
            $table->string('serial_number')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->date('date_approved')->nullable();
            $table->unsignedBigInteger('business_id');
            $table->unsignedBigInteger('asset_type_id');
            $table->unsignedBigInteger('employee_id')->nullable();
            $table
                ->foreign('business_id')
                ->references('id')
                ->on('businesses')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
            $table
                ->foreign('asset_type_id')
                ->references('id')
                ->on('asset_types')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
            $table
                ->foreign('employee_id')
                ->references('id')
                ->on('employees')
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
        Schema::dropIfExists('assets');
    }
};
