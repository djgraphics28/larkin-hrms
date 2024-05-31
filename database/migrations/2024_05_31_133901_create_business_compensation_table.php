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
        Schema::create('business_compensation', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount',16, 2);
            $table->enum('label',['National','Expatriate']);
            $table->unsignedBigInteger('business_id')->nullable();
            $table->unsignedBigInteger('compensation_item_id')->nullable();
            $table
                ->foreign('business_id')
                ->references('id')
                ->on('businesses')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
            $table
                ->foreign('compensation_item_id')
                ->references('id')
                ->on('compensation_items')
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
        Schema::dropIfExists('business_compensation');
    }
};
