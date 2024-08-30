<?php

namespace App\Contracts;

use App\Contracts\Helpers\HasTimestampContract;

interface ProductTypeContract extends HasTimestampContract
{

    public const LOAN_HOURS_TYPES = [
        self::LOAN_TYPE_8,
        self::LOAN_TYPE_16,
        self::LOAN_TYPE_24,
    ];

    public const LOAN_TYPE_8  = 8;
    public const LOAN_TYPE_16 = 16;
    public const LOAN_TYPE_24 = 24;

    public const FIELD_LIST = [
        self::FIELD_ID,
        self::FIELD_TITLE,
        self::FIELD_PRICE,
        self::FIELD_IS_AVAILABLE_FOR_SALE,
        self::FIELD_IS_AVAILABLE_FOR_LOAN,
        self::FIELD_PRICE_FOR_8_HOURS_LOAN,
        self::FIELD_PRICE_FOR_16_HOURS_LOAN,
        self::FIELD_PRICE_FOR_24_HOURS_LOAN,
    ];

    public const FIELD_ID                      = 'id';
    public const FIELD_TITLE                   = 'title';
    public const FIELD_PRICE                   = 'price';
    public const FIELD_IS_AVAILABLE_FOR_SALE   = 'is_available_for_sale';
    public const FIELD_IS_AVAILABLE_FOR_LOAN   = 'is_available_for_loan';
    public const FIELD_PRICE_FOR_8_HOURS_LOAN  = 'price_for_8_hours_loan';
    public const FIELD_PRICE_FOR_16_HOURS_LOAN = "price_for_16_hours_loan";
    public const FIELD_PRICE_FOR_24_HOURS_LOAN = "price_for_24_hours_loan";

}
