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
        Schema::create('save_filters', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->json('selected_department')->nullable();
            $table->json('selected_designation')->nullable();
            $table->json('employee_lists');
            $table->unsignedBigInteger('business_id');
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
        Schema::dropIfExists('save_filters');
    }
};
