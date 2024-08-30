<?php

namespace App\Models;

use App\Contracts\ProductContract;
use App\Contracts\ProductTransactionHistoryContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable  = [
        ProductContract::FIELD_PRODUCT_TYPE_ID,
        ProductContract::FIELD_USER_ID,
        ProductContract::FIELD_CODE,
        ProductContract::FIELD_STATUS,
        ProductContract::FIELD_LOAN_STARTED,
        ProductContract::FIELD_LOAN_UNTIL,
    ];

    public function transactionHistory()
    {
        return $this->hasMany(ProductTransactionHistory::class, ProductTransactionHistoryContract::FIELD_PRODUCT_ID);
    }
}
