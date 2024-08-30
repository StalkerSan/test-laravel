<?php

namespace App\Entity;

use App\Entity\Base\BaseDTO;
use App\Models\User;

class ProductLoanDTO extends BaseDTO
{

    protected User $user;

    protected ?int $product_type_id = null;

    protected ?string $product_code = null;
    protected int     $hours;

    public function getProductTypeId(): ?int
    {
        return $this->product_type_id;
    }

    public function getHours(): int
    {
        return $this->hours;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getProductCode(): ?string
    {
        return $this->product_code;
    }

}
