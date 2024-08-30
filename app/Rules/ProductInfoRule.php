<?php

namespace App\Rules;

use App\Contracts\Service\ProductRepositoryContract;
use App\Contracts\UserContract;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductInfoRule implements ValidationRule
{
    private ProductRepositoryContract $productRepositoryContract;

    public function __construct()
    {
        $this->productRepositoryContract = app()->make(ProductRepositoryContract::class);
    }

    /**
     * Run the validation rule.
     *
     * @param \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            $productDTO = $this->productRepositoryContract->getProductInfoByUuid($value);
            $user       = Auth::user();
            if ($user->{UserContract::FIELD_ID} !== $productDTO->getUserId()) {
                $fail('The selected code is invalid');
            }

        } catch (ModelNotFoundException $e) {
            $fail('The selected code is invalid');
        }

    }
}
