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
        Schema::create('week_day_workshift', function (Blueprint $table) {
            $table->unsignedBigInteger('workshift_id');
            $table->unsignedBigInteger('week_day_id');
            // $table->timestamps();

            // Define foreign key constraints
            $table->foreign('workshift_id')->references('id')->on('workshifts')->onDelete('cascade');
            $table->foreign('week_day_id')->references('id')->on('week_days')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('week_day_workshift');
    }
};
