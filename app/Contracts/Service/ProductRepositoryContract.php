<?php

namespace App\Contracts\Service;

use App\Entity\ProductDTO;
use App\Entity\ProductTransactionHistoryDTO;
use App\Entity\ProductTypeDTO;
use App\Entity\ProductUpdateDTO;
use App\Models\Product;

interface ProductRepositoryContract
{
    public function getProductTypeById(int $id): ProductTypeDTO;

    public function getProductInfoByUuid(string $uuid): ProductDTO;

    public function chargeBalance(float $price, int $user_id): void;
    public function createProduct(ProductDTO $DTO): ProductDTO;
    public function updateProduct(ProductUpdateDTO $DTO): ProductDTO;
    public function createTransactionHistory(ProductTransactionHistoryDTO $DTO): ProductTransactionHistoryDTO;


}
