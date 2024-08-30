<?php

namespace App\Entity;

use App\Entity\Base\BaseDTO;
use Ramsey\Collection\Collection;

class ProductDTO  extends  BaseDTO
{
    protected int $id;
    protected int $user_id;
    protected int $product_type_id;
    protected string $code;
    protected ?string $loan_started = null;
    protected ?string $loan_until = null;
    protected ?string $created_at = null;
    protected ?string $updated_at = null;
    protected ?array $transaction_history = null;


    protected string $status;

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getTransactionHistory(): ?array
    {
        return $this->transaction_history;
    }




    public function getId(): int
    {
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function getProductTypeId(): int
    {
        return $this->product_type_id;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getLoanStarted(): ?string
    {
        return $this->loan_started;
    }

    public function getLoanUntil(): ?string
    {
        return $this->loan_until;
    }

    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): string
    {
        return $this->updated_at;
    }



}
