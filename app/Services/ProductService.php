<?php

namespace App\Services;

use App\Contracts\ProductContract;
use App\Contracts\ProductTransactionHistoryContract;
use App\Contracts\ProductTypeContract;
use App\Contracts\Service\ProductRepositoryContract;
use App\Entity\ProductBuyDTO;
use App\Entity\ProductDTO;
use App\Entity\ProductLoanDTO;
use App\Entity\ProductTransactionHistoryDTO;
use App\Entity\ProductUpdateDTO;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class ProductService
{
    private ProductRepositoryContract $productRepositoryContract;

    public function __construct(ProductRepositoryContract $productRepositoryContract)
    {
        $this->productRepositoryContract = $productRepositoryContract;
    }

    public function buy(ProductBuyDTO $productBuyDTO): array
    {
        $productTypeDTO = $this->productRepositoryContract->getProductTypeById($productBuyDTO->getProductId());
        $productDTO     = ProductDTO::create(
            [
                ProductContract::FIELD_USER_ID         => $productBuyDTO->getUser()->id,
                ProductContract::FIELD_PRODUCT_TYPE_ID => $productBuyDTO->getProductId(),
                ProductContract::FIELD_CODE            => (string)Str::uuid(),
                ProductContract::FIELD_STATUS          => ProductContract::STATUS_SOLD,
            ]
        );
        try {
            DB::beginTransaction();
            $this->productRepositoryContract->chargeBalance($productTypeDTO->getPrice(), $productBuyDTO->getUser()->id);
            $resultProductDto             = $this->productRepositoryContract->createProduct($productDTO);
            $productTransactionHistoryDTO = ProductTransactionHistoryDTO::create([
                ProductTransactionHistoryContract::FIELD_PRODUCT_ID       => $resultProductDto->getId(),
                ProductTransactionHistoryContract::FIELD_USER_ID          => $resultProductDto->getUserId(),
                ProductTransactionHistoryContract::FIELD_TRANSACTION_TYPE => ProductTransactionHistoryContract::TRANSACTION_TYPE_PURCHASE,
                ProductTransactionHistoryContract::FIELD_PRICE            => $productTypeDTO->getPrice(),
                ProductTransactionHistoryContract::FIELD_DATETIME_APPLIED => Carbon::now()->toDateTimeString()
            ]);
            $resultTransactionDto         = $this->productRepositoryContract->createTransactionHistory($productTransactionHistoryDTO);
            DB::commit();

        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }

        return ['product' => $resultProductDto->toArray(), 'transaction' => $resultTransactionDto->toArray()];

    }

    public function getProductStatus(string $uuid): array
    {
        return $this->productRepositoryContract->getProductInfoByUuid($uuid)->toArray();
    }

    public function loan(ProductLoanDTO $productLoanDTO): array
    {
        $productTypeDTO           = $this->productRepositoryContract->getProductTypeById($productLoanDTO->getProductTypeId());
        $productLoanStartDateTime = Carbon::now()->toDateTimeString();
        $productLoanEndDateTime   = Carbon::now()->addHours($productLoanDTO->getHours())->toDateTimeString();
        $productDTO               = ProductDTO::create(
            [
                ProductContract::FIELD_USER_ID         => $productLoanDTO->getUser()->id,
                ProductContract::FIELD_PRODUCT_TYPE_ID => $productLoanDTO->getProductTypeId(),
                ProductContract::FIELD_CODE            => (string)Str::uuid(),
                ProductContract::FIELD_STATUS          => ProductContract::STATUS_LOANED,
                ProductContract::FIELD_LOAN_STARTED    => $productLoanStartDateTime,
                ProductContract::FIELD_LOAN_UNTIL      => $productLoanEndDateTime
            ]
        );
        $price                    = match (true) {
            $productLoanDTO->getHours() == ProductTypeContract::LOAN_TYPE_8  => $productTypeDTO->getPriceFor8HoursLoan(),
            $productLoanDTO->getHours() == ProductTypeContract::LOAN_TYPE_16 => $productTypeDTO->getPriceFor16HoursLoan(),
            $productLoanDTO->getHours() == ProductTypeContract::LOAN_TYPE_24 => $productTypeDTO->getPriceFor24HoursLoan(),
        };
        try {
            DB::beginTransaction();
            $this->productRepositoryContract->chargeBalance($price, $productLoanDTO->getUser()->id);
            $resultProductDto             = $this->productRepositoryContract->createProduct($productDTO);
            $productTransactionHistoryDTO = ProductTransactionHistoryDTO::create([
                ProductTransactionHistoryContract::FIELD_PRODUCT_ID       => $resultProductDto->getId(),
                ProductTransactionHistoryContract::FIELD_USER_ID          => $resultProductDto->getUserId(),
                ProductTransactionHistoryContract::FIELD_TRANSACTION_TYPE => ProductTransactionHistoryContract::TRANSACTION_TYPE_LOAN,
                ProductTransactionHistoryContract::FIELD_PRICE            => $price,
                ProductTransactionHistoryContract::FIELD_DATETIME_APPLIED => $productLoanStartDateTime,
                ProductTransactionHistoryContract::FIELD_LOAN_HOURS       => $productLoanDTO->getHours(),
                ProductTransactionHistoryContract::FIELD_LOAN_UNTIL       => $productLoanEndDateTime,
            ]);
            $resultTransactionDto         = $this->productRepositoryContract->createTransactionHistory($productTransactionHistoryDTO);
            DB::commit();

        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }

        return ['product' => $resultProductDto->toArray(), 'transaction' => $resultTransactionDto->toArray()];

    }

    public function extendLoan(ProductLoanDTO $productLoanDTO): array
    {

        $productDTO                     = $this->productRepositoryContract->getProductInfoByUuid($productLoanDTO->getProductCode());
        $productTypeDTO                 = $this->productRepositoryContract->getProductTypeById($productDTO->getProductTypeId());
        $productExtendLoanStartDateTime = Carbon::now()->toDateTimeString();
        $productExtendLoanEndDateTime   = Carbon::parse($productDTO->getLoanUntil())->addHours($productLoanDTO->getHours())->toDateTimeString();
        $productUpdateDTO               = ProductUpdateDTO::create(
            [
                'product_id'                      => $productDTO->getId(),
                ProductContract::FIELD_STATUS     => ProductContract::STATUS_LOANED,
                ProductContract::FIELD_LOAN_UNTIL => $productExtendLoanEndDateTime
            ]
        );
        $price                          = match (true) {
            $productLoanDTO->getHours() == ProductTypeContract::LOAN_TYPE_8  => $productTypeDTO->getPriceFor8HoursLoan(),
            $productLoanDTO->getHours() == ProductTypeContract::LOAN_TYPE_16 => $productTypeDTO->getPriceFor16HoursLoan(),
            $productLoanDTO->getHours() == ProductTypeContract::LOAN_TYPE_24 => $productTypeDTO->getPriceFor24HoursLoan(),
        };
        try {
            DB::beginTransaction();
            $this->productRepositoryContract->chargeBalance($price, $productLoanDTO->getUser()->id);
            $resultProductDto             = $this->productRepositoryContract->updateProduct($productUpdateDTO);
            $productTransactionHistoryDTO = ProductTransactionHistoryDTO::create([
                ProductTransactionHistoryContract::FIELD_PRODUCT_ID       => $resultProductDto->getId(),
                ProductTransactionHistoryContract::FIELD_USER_ID          => $resultProductDto->getUserId(),
                ProductTransactionHistoryContract::FIELD_TRANSACTION_TYPE => ProductTransactionHistoryContract::TRANSACTION_TYPE_EXTEND_LOAN,
                ProductTransactionHistoryContract::FIELD_PRICE            => $price,
                ProductTransactionHistoryContract::FIELD_DATETIME_APPLIED => $productExtendLoanStartDateTime,
                ProductTransactionHistoryContract::FIELD_LOAN_HOURS       => $productLoanDTO->getHours(),
                ProductTransactionHistoryContract::FIELD_LOAN_UNTIL       => $productExtendLoanEndDateTime,
            ]);
            $resultTransactionDto         = $this->productRepositoryContract->createTransactionHistory($productTransactionHistoryDTO);
            DB::commit();

        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }

        return ['product' => $resultProductDto->toArray(), 'transaction' => $resultTransactionDto->toArray()];

    }

    public function returnFromLoan()
    {

    }
}
