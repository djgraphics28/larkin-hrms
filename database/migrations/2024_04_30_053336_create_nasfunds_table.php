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
        Schema::create('nasfunds', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id');
            $table->string('fortnight_id');
            $table->decimal('pay', 10, 2);
            $table->decimal('ER', 10, 2);
            $table->decimal('EE', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nasfunds');
    }
};
