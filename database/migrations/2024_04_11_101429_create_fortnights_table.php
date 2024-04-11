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
        Schema::create('fortnights', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('fn_number');
            $table->string('code')->unique(); //this should start with FN + 2 digit end of year + number of fortnight
            $table->date('start');
            $table->date('end');
            $table->string('year');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fortnights');
    }
};
