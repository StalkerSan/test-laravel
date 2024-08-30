<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Contracts\ProductTransactionHistoryContract;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_transaction_history', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->enum('transaction_type', ProductTransactionHistoryContract::AVAILABLE_TRANSACTION_TYPES );
            $table->float('price');
            $table->dateTime('datetime_applied')->nullable();
            $table->integer('loan_hours')->nullable();
            $table->dateTime('loan_until')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_transaction_history');
    }
};
