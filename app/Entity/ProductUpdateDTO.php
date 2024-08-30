<?php

namespace App\Entity;

use App\Entity\Base\BaseDTO;

class ProductUpdateDTO extends BaseDTO
{

    protected int $product_id;

    protected string $status;

    protected ?string $loan_until = null;

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getProductId(): int
    {
        return $this->product_id;
    }

    public function getLoanUntil(): ?string
    {
        return $this->loan_until;
    }


}
