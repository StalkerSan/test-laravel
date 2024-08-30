<?php

namespace App\Http\Requests;

use App\Contracts\ProductTypeContract;
use App\Rules\ExtendLoanRule;
use App\Rules\ProductInfoRule;
use App\Rules\ValidateForLoanProduct;
use Illuminate\Foundation\Http\FormRequest;

class ExtendLoanRequest extends FormRequest
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
            'code' => ['required', 'string', 'exists:mysql.products,code', new ProductInfoRule(), new ExtendLoanRule(data_get(request(),'hours'))],
            'hours' => 'required|in:'.implode(',',ProductTypeContract::LOAN_HOURS_TYPES),
        ];
    }

}
