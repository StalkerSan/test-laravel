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
        Schema::table('product_types', function (Blueprint $table) {
            $table->float('price_for_8_hours_loan')->nullable()->unsigned();
            $table->float('price_for_16_hours_loan')->nullable()->unsigned();
            $table->float('price_for_24_hours_loan')->nullable()->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_types', function (Blueprint $table) {
            //
        });
    }
};
