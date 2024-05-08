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
        Schema::create('payruns', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fortnight_id');
            $table->unsignedBigInteger('business_id');
            $table->string('remarks');
            $table->timestamps();
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
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payruns');
    }
};
