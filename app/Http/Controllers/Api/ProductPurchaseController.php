<?php

namespace App\Http\Controllers\Api;

use App\Entity\ProductBuyDTO;
use App\Entity\ProductLoanDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\BuyProductRequest;
use App\Http\Requests\ExtendLoanRequest;
use App\Http\Requests\LoanProductRequest;
use App\Http\Requests\ProductInfoRequest;
use App\Http\Requests\ReturnFromLoanRequest;
use App\Rules\ProductInfoRule;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ProductPurchaseController extends Controller
{
    private ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function buy(BuyProductRequest $request)
    {
        try {
            $dto    = ProductBuyDTO::create(['user' => Auth::user(), 'product_id' => $request->product_id]);
            $result = $this->productService->buy($dto);
            return response()->json(
                [
                    'data' => $result
                ],
                200
            );

        } catch (\Throwable $exception) {
            Log::error($exception->getMessage());
            return response()->json(
                [
                    'error' => 'server error'
                ],
                500
            );
        }
    }

    public function getProductInfo(Request $request)
    {
        validator($request->route()->parameters(), [
            'code' => ['required', 'string', 'exists:mysql.products,code'],
        ])->validate();
        validator($request->route()->parameters(), [
            'code' => [new ProductInfoRule()],
        ])->validate();
        $code = $request->code;
        return response()->json(
            [
                'data' => $this->productService->getProductStatus($code)
            ],
            200
        );
    }

    public function loan(LoanProductRequest $request)
    {
        try {
            $dto    = ProductLoanDTO
                ::create([
                    'user'            => Auth::user(),
                    'product_type_id' => $request->product_type_id,
                    'hours'           => $request->hours
                ]);
            $result = $this->productService->loan($dto);
            return response()->json(
                [
                    'data' => $result
                ],
                200
            );

        } catch (\Throwable $exception) {
            Log::error($exception->getMessage());
            return response()->json(
                [
                    'error' => 'server error'
                ],
                500
            );

        }
    }

    public function extendLoan(ExtendLoanRequest $request)
    {
        try {
            $dto    = ProductLoanDTO
                ::create([
                    'user'         => Auth::user(),
                    'product_code' => $request->code,
                    'hours'        => $request->hours
                ]);
            $result = $this->productService->extendLoan($dto);
            return response()->json(
                [
                    'data' => $result
                ],
                200
            );

        } catch (\Throwable $exception) {
            Log::error($exception->getMessage());
            return response()->json(
                [
                    'error' => 'server error'
                ],
                500
            );

        }
    }

    public
    function returnFromLoan(
        ReturnFromLoanRequest $request
    ) {

    }

}
