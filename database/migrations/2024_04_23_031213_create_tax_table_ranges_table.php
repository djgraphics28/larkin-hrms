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
        Schema::create('tax_table_ranges', function (Blueprint $table) {
            $table->id();
            $table->string('description');
            $table->decimal('from',16);
            $table->decimal('to',16)->nullable();
            $table->float('percentage');
            $table->unsignedBigInteger('tax_table_id');
            $table
                ->foreign('tax_table_id')
                ->references('id')
                ->on('tax_tables')
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
        Schema::dropIfExists('tax_table_ranges');
    }
};
