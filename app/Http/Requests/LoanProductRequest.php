<?php

namespace App\Http\Requests;

use App\Contracts\ProductTypeContract;
use App\Rules\ExtendLoanRule;
use App\Rules\ProductInfoRule;
use App\Rules\ValidateForLoanProduct;
use App\Rules\ValidateForSaleProduct;
use Illuminate\Foundation\Http\FormRequest;

class LoanProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        return [
            'hours' => 'required|in:'.implode(',',ProductTypeContract::LOAN_HOURS_TYPES),
            'product_type_id' => ['required', 'integer', 'exists:mysql.product_types,id', new ValidateForLoanProduct(data_get(request(),'hours')) ],
        ];
    }
}
