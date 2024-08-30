<?php

namespace App\Entity;

use App\Entity\Base\BaseDTO;

class ProductTypeDTO  extends BaseDTO
{
    protected int    $id;
    protected int    $price;
    protected string $title;
    protected bool   $is_available_for_sale;
    protected bool   $is_available_for_loan;
    protected int    $price_for_8_hours_loan;
    protected int    $price_for_16_hours_loan;
    protected int    $price_for_24_hours_loan;

    protected ?string $created_at =null;
    protected ?string $updated_at = null;

    protected $productTransactionHistory;

    /**
     * @return mixed
     */
    public function getProductTransactionHistory()
    {
        return $this->productTransactionHistory;
    }


    public function getId(): int
    {
        return $this->id;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function isAvailableForSale(): bool
    {
        return $this->is_available_for_sale;
    }

    public function isAvailableForLoan(): bool
    {
        return $this->is_available_for_loan;
    }

    public function getPriceFor8HoursLoan(): int
    {
        return $this->price_for_8_hours_loan;
    }

    public function getPriceFor16HoursLoan(): int
    {
        return $this->price_for_16_hours_loan;
    }

    public function getPriceFor24HoursLoan(): int
    {
        return $this->price_for_24_hours_loan;
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
