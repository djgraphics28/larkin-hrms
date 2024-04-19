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
            Schema::create('business_company_bank', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('company_bank_id');
                $table->unsignedBigInteger('business_id');

                $table->timestamps();
                $table
                    ->foreign('company_bank_id')
                    ->references('id')
                    ->on('company_banks')
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
            Schema::dropIfExists('business_company_banks');
        }
    };
