<?php

namespace App\Entity;

use App\Entity\Base\BaseDTO;

class ProductTransactionHistoryDTO extends BaseDTO
{

    protected ?int $id = null;
    protected int $product_id;

    protected ?ProductDTO $productDTO =null;

    protected int $user_id;

    protected string $transaction_type;
    protected float  $price;

    protected string $datetime_applied;
    protected ?int    $loan_hours = null;
    protected ?string $loan_until =null;


    public function getId(): int
    {
        return $this->id;
    }

    public function getProductId(): int
    {
        return $this->product_id;
    }

    public function getProductDTO(): ProductDTO
    {
        return $this->productDTO;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function getTransactionType(): string
    {
        return $this->transaction_type;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getDatetimeApplied(): string
    {
        return $this->datetime_applied;
    }

    public function getLoanHours(): ?int
    {
        return $this->loan_hours;
    }

    public function getLoanUntil(): ?string
    {
        return $this->loan_until;
    }


}
