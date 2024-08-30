<?php

namespace App\Repositories;

use App\Contracts\ProductContract;
use App\Contracts\ProductTransactionHistoryContract;
use App\Contracts\ProductTypeContract;
use App\Contracts\Service\ProductRepositoryContract;
use App\Contracts\UserContract;
use App\Entity\ProductDTO;
use App\Entity\ProductTransactionHistoryDTO;
use App\Entity\ProductTypeDTO;
use App\Entity\ProductUpdateDTO;
use App\Models\Product;
use App\Models\ProductTransactionHistory;
use App\Models\ProductType;
use App\Models\User;
use Illuminate\Support\Collection;

class ProductRepositoryEloquent implements ProductRepositoryContract
{

    public function getProductTypeById(int $id): ProductTypeDTO
    {
        $productType = ProductType::query()->findOrFail($id);
        return ProductTypeDTO::create([
            ProductTypeContract::FIELD_ID                      => data_get($productType, ProductTypeContract::FIELD_ID),
            ProductTypeContract::FIELD_PRICE                   => data_get($productType,
                ProductTypeContract::FIELD_PRICE),
            ProductTypeContract::FIELD_TITLE                   => data_get($productType,
                ProductTypeContract::FIELD_TITLE),
            ProductTypeContract::FIELD_IS_AVAILABLE_FOR_SALE   => data_get($productType,
                ProductTypeContract::FIELD_IS_AVAILABLE_FOR_SALE),
            ProductTypeContract::FIELD_IS_AVAILABLE_FOR_LOAN   => data_get($productType,
                ProductTypeContract::FIELD_IS_AVAILABLE_FOR_LOAN),
            ProductTypeContract::FIELD_CREATED_AT              => data_get($productType,
                ProductTypeContract::FIELD_CREATED_AT),
            ProductTypeContract::FIELD_UPDATED_AT              => data_get($productType,
                ProductTypeContract::FIELD_UPDATED_AT),
            ProductTypeContract::FIELD_PRICE_FOR_8_HOURS_LOAN  => data_get($productType,
                ProductTypeContract::FIELD_PRICE_FOR_8_HOURS_LOAN),
            ProductTypeContract::FIELD_PRICE_FOR_16_HOURS_LOAN => data_get($productType,
                ProductTypeContract::FIELD_PRICE_FOR_16_HOURS_LOAN),
            ProductTypeContract::FIELD_PRICE_FOR_24_HOURS_LOAN => data_get($productType,
                ProductTypeContract::FIELD_PRICE_FOR_24_HOURS_LOAN),
        ]);
    }

    public function getProductInfoByUuid(string $uuid): ProductDTO
    {
        $item = Product
            ::query()
            ->with('transactionHistory')
            ->where(ProductContract::FIELD_CODE, $uuid)
            ->firstOrFail();
        return ProductDTO::create([
            ProductContract::FIELD_ID => data_get($item, ProductContract::FIELD_ID),
            ProductContract::FIELD_USER_ID => data_get($item, ProductContract::FIELD_USER_ID),
            ProductContract::FIELD_PRODUCT_TYPE_ID => data_get($item, ProductContract::FIELD_PRODUCT_TYPE_ID),
            ProductContract::FIELD_CODE => data_get($item, ProductContract::FIELD_CODE),
            ProductContract::FIELD_STATUS => data_get($item, ProductContract::FIELD_STATUS),
            ProductContract::FIELD_LOAN_UNTIL => data_get($item, ProductContract::FIELD_LOAN_UNTIL),
            ProductContract::FIELD_LOAN_STARTED => data_get($item, ProductContract::FIELD_LOAN_STARTED),
            'transaction_history' => $this->getTransactionHistoryArray(isset($item)  ? $item->transactionHistory()->get(): new Collection()),
        ]);
    }

    private function getTransactionHistoryArray(Collection $collection) :array
    {
        $result = [];
        foreach($collection as $item) {
            $result[] = ProductTransactionHistoryDTO::create([
                ProductTransactionHistoryContract::FIELD_ID               => data_get($item,
                    ProductTransactionHistoryContract::FIELD_ID),
                ProductTransactionHistoryContract::FIELD_USER_ID          => data_get($item,
                    ProductTransactionHistoryContract::FIELD_USER_ID),
                ProductTransactionHistoryContract::FIELD_PRODUCT_ID       => data_get($item,
                    ProductTransactionHistoryContract::FIELD_PRODUCT_ID),
                ProductTransactionHistoryContract::FIELD_TRANSACTION_TYPE => data_get($item,
                    ProductTransactionHistoryContract::FIELD_TRANSACTION_TYPE),
                ProductTransactionHistoryContract::FIELD_PRICE            => data_get($item,
                    ProductTransactionHistoryContract::FIELD_PRICE),
                ProductTransactionHistoryContract::FIELD_DATETIME_APPLIED => data_get($item,
                    ProductTransactionHistoryContract::FIELD_DATETIME_APPLIED),
                ProductTransactionHistoryContract::FIELD_LOAN_HOURS       => data_get($item,
                    ProductTransactionHistoryContract::FIELD_LOAN_HOURS),
                ProductTransactionHistoryContract::FIELD_LOAN_UNTIL       => data_get($item,
                    ProductTransactionHistoryContract::FIELD_LOAN_UNTIL),
            ])->toArray();
        }
        return $result;
    }

    public function chargeBalance(float $price, int $user_id): void
    {
        $user                                = User::query()->findOrFail($user_id);
        $user->{UserContract::FIELD_BALANCE} -= $price;
        $user->save();
    }


    public function createProduct(ProductDTO $DTO): ProductDTO
    {
        $product = Product::query()->create([
            ProductContract::FIELD_USER_ID         => $DTO->getUserId(),
            ProductContract::FIELD_PRODUCT_TYPE_ID => $DTO->getProductTypeId(),
            ProductContract::FIELD_STATUS          => $DTO->getStatus(),
            ProductContract::FIELD_LOAN_STARTED    => $DTO->getLoanStarted(),
            ProductContract::FIELD_LOAN_UNTIL      => $DTO->getLoanUntil(),
            ProductContract::FIELD_CODE            => $DTO->getCode()
        ]);
        return ProductDTO::create([
            ProductContract::FIELD_ID              => data_get($product, ProductContract::FIELD_ID),
            ProductContract::FIELD_USER_ID         => data_get($product, ProductContract::FIELD_USER_ID),
            ProductContract::FIELD_PRODUCT_TYPE_ID => data_get($product, ProductContract::FIELD_PRODUCT_TYPE_ID),
            ProductContract::FIELD_LOAN_STARTED    => data_get($product, ProductContract::FIELD_LOAN_STARTED),
            ProductContract::FIELD_LOAN_UNTIL      => data_get($product, ProductContract::FIELD_LOAN_UNTIL),
            ProductContract::FIELD_CODE            => data_get($product, ProductContract::FIELD_CODE),
            ProductContract::FIELD_STATUS          => data_get($product, ProductContract::FIELD_STATUS)
        ]);
    }

    public function updateProduct(ProductUpdateDTO $DTO): ProductDTO
    {
        $product = Product::query()->findOrFail($DTO->getProductId());
        $product->{ProductContract::FIELD_STATUS} = $DTO->getStatus();
        if($DTO->getLoanUntil()) {
            $product->{ProductContract::FIELD_LOAN_UNTIL} = $DTO->getLoanUntil();
        }
        $product->save();
        return ProductDTO::create([
            ProductContract::FIELD_ID              => data_get($product, ProductContract::FIELD_ID),
            ProductContract::FIELD_USER_ID         => data_get($product, ProductContract::FIELD_USER_ID),
            ProductContract::FIELD_PRODUCT_TYPE_ID => data_get($product, ProductContract::FIELD_PRODUCT_TYPE_ID),
            ProductContract::FIELD_LOAN_STARTED    => data_get($product, ProductContract::FIELD_LOAN_STARTED),
            ProductContract::FIELD_LOAN_UNTIL      => data_get($product, ProductContract::FIELD_LOAN_UNTIL),
            ProductContract::FIELD_CODE            => data_get($product, ProductContract::FIELD_CODE),
            ProductContract::FIELD_STATUS          => data_get($product, ProductContract::FIELD_STATUS)
        ]);

    }

    public function createTransactionHistory(ProductTransactionHistoryDTO $DTO): ProductTransactionHistoryDTO
    {
        $transactionHistory = ProductTransactionHistory::query()->create([
            ProductTransactionHistoryContract::FIELD_USER_ID          => $DTO->getUserId(),
            ProductTransactionHistoryContract::FIELD_PRODUCT_ID       => $DTO->getProductId(),
            ProductTransactionHistoryContract::FIELD_TRANSACTION_TYPE => $DTO->getTransactionType(),
            ProductTransactionHistoryContract::FIELD_PRICE            => $DTO->getPrice(),
            ProductTransactionHistoryContract::FIELD_DATETIME_APPLIED => $DTO->getDatetimeApplied(),
            ProductTransactionHistoryContract::FIELD_LOAN_HOURS       => $DTO->getLoanHours(),
            ProductTransactionHistoryContract::FIELD_LOAN_UNTIL       => $DTO->getLoanUntil(),
        ]);
        return ProductTransactionHistoryDTO::create([
            ProductTransactionHistoryContract::FIELD_ID               => data_get($transactionHistory,
                ProductTransactionHistoryContract::FIELD_ID),
            ProductTransactionHistoryContract::FIELD_USER_ID          => data_get($transactionHistory,
                ProductTransactionHistoryContract::FIELD_USER_ID),
            ProductTransactionHistoryContract::FIELD_PRODUCT_ID       => data_get($transactionHistory,
                ProductTransactionHistoryContract::FIELD_PRODUCT_ID),
            ProductTransactionHistoryContract::FIELD_TRANSACTION_TYPE => data_get($transactionHistory,
                ProductTransactionHistoryContract::FIELD_TRANSACTION_TYPE),
            ProductTransactionHistoryContract::FIELD_PRICE            => data_get($transactionHistory,
                ProductTransactionHistoryContract::FIELD_PRICE),
            ProductTransactionHistoryContract::FIELD_DATETIME_APPLIED => data_get($transactionHistory,
                ProductTransactionHistoryContract::FIELD_DATETIME_APPLIED),
            ProductTransactionHistoryContract::FIELD_LOAN_HOURS       => data_get($transactionHistory,
                ProductTransactionHistoryContract::FIELD_LOAN_HOURS),
            ProductTransactionHistoryContract::FIELD_LOAN_UNTIL       => data_get($transactionHistory,
                ProductTransactionHistoryContract::FIELD_LOAN_UNTIL),
        ]);
    }


}
