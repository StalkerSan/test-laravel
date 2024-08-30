<?php

namespace App\Entity;

use App\Entity\Base\BaseDTO;
use App\Models\User;

class ProductBuyDTO extends  BaseDTO
{

    protected User $user;

    protected int $product_id;

    public function getUser(): User
    {
        return $this->user;
    }

    public function getProductId(): int
    {
        return $this->product_id;
    }

}
