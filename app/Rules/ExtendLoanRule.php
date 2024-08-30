<?php

namespace App\Rules;

use App\Contracts\ProductContract;
use App\Contracts\ProductTransactionHistoryContract;
use App\Contracts\Service\ProductRepositoryContract;
use App\Contracts\UserContract;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Auth;

class ExtendLoanRule implements ValidationRule
{

    private ProductRepositoryContract $productRepositoryContract;

    private ?int $hours;

    public function __construct(?int $hours)
    {
        $this->productRepositoryContract = app()->make(ProductRepositoryContract::class);
        $this->hours = $hours;
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $productDTO = $this->productRepositoryContract->getProductInfoByUuid($value);
        if($productDTO->getStatus()!== ProductContract::STATUS_LOANED) {
            $fail('product is not loaned now');
            return;
        }
        $sumHours = 0;
        foreach($productDTO->getTransactionHistory() as $transaction) {
            $sumHours+=data_get($transaction,ProductTransactionHistoryContract::FIELD_LOAN_HOURS);
        }
        if($sumHours + $this->hours >24) {
            $fail('product is not available for loan more than 24 hours total');
        }
    }
}
