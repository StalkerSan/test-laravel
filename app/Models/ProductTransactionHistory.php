<?php

namespace App\Models;

use App\Contracts\ProductContract;
use App\Contracts\ProductTransactionHistoryContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductTransactionHistory extends Model
{
    protected $table = 'product_transaction_history';
    use HasFactory;

    protected $fillable  = [
        ProductTransactionHistoryContract::FIELD_USER_ID,
        ProductTransactionHistoryContract::FIELD_PRODUCT_ID,
        ProductTransactionHistoryContract::FIELD_TRANSACTION_TYPE,
        ProductTransactionHistoryContract::FIELD_LOAN_HOURS,
        ProductTransactionHistoryContract::FIELD_LOAN_UNTIL,
        ProductTransactionHistoryContract::FIELD_DATETIME_APPLIED,
        ProductTransactionHistoryContract::FIELD_PRICE,
    ];
}
