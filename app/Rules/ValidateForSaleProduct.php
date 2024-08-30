<?php

namespace App\Rules;

use App\Contracts\Service\ProductRepositoryContract;
use App\Contracts\UserContract;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Auth;

class ValidateForSaleProduct implements ValidationRule
{

    private ProductRepositoryContract $productRepositoryContract;

    public function __construct()
    {
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
        if(!$productDTO->isAvailableForSale()) {
            $fail('product is not available for sale');
        }
        $user = Auth::user();
        if($user->{UserContract::FIELD_BALANCE} < $productDTO->getPrice()) {
            $fail('not enough balance');
        }
    }
}
