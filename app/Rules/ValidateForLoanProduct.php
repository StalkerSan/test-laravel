<?php

namespace App\Rules;

use App\Contracts\ProductTypeContract;
use App\Contracts\Service\ProductRepositoryContract;
use App\Contracts\UserContract;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Auth;

class ValidateForLoanProduct implements ValidationRule
{
    private ProductRepositoryContract $productRepositoryContract;

    private  ?int $loanPeriod;

    public function __construct($loanPeriod)
    {
        $this->loanPeriod = $loanPeriod;
        $this->productRepositoryContract = app()->make(ProductRepositoryContract::class);
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $productDTO = $this->productRepositoryContract->getProductTypeById($value);
        if(!$productDTO->isAvailableForLoan()) {
            $fail('product is not available for loan');
        }
        $user = Auth::user();
        switch ($this->loanPeriod) {
            case ProductTypeContract::LOAN_TYPE_8:
                if($user->{UserContract::FIELD_BALANCE} < $productDTO->getPriceFor8HoursLoan()) {
                    $fail('not enough balance');
                }
                return;
            case ProductTypeContract::LOAN_TYPE_16:
                if($user->{UserContract::FIELD_BALANCE} < $productDTO->getPriceFor16HoursLoan()) {
                    $fail('not enough balance');
                }
                return;
            case ProductTypeContract::LOAN_TYPE_24:
                if($user->{UserContract::FIELD_BALANCE} < $productDTO->getPriceFor24HoursLoan()) {
                    $fail('not enough balance');
                }
                return;
        }

    }
}
