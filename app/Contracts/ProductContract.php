<?php

namespace App\Contracts;

use App\Contracts\Helpers\HasTimestampContract;

interface ProductContract extends HasTimestampContract
{
    public const FIELD_LIST = [
        self::FIELD_ID,
        self::FIELD_USER_ID,
        self::FIELD_PRODUCT_TYPE_ID,
        self::FIELD_CODE,
        self::FIELD_STATUS,
        self::FIELD_LOAN_STARTED,
        self::FIELD_LOAN_UNTIL,
    ];

    public const FIELD_ID = 'id';
    public const FIELD_USER_ID = 'user_id';
    public const FIELD_PRODUCT_TYPE_ID = 'product_type_id';
    public const FIELD_CODE = 'code';
    public const FIELD_STATUS = 'status';
    public const FIELD_LOAN_STARTED = "loan_started";
    public const FIELD_LOAN_UNTIL = "loan_until";

    public const AVAILABLE_STATUSES = [
        self::STATUS_LOANED,
        self::STATUS_REFUNDED,
        self::STATUS_RETURNED_FROM_LOAN,
        self::STATUS_SOLD
    ];
    public const STATUS_SOLD ='sold';
    public const STATUS_LOANED = 'loaned';
    public const STATUS_RETURNED_FROM_LOAN = 'returned_from_loan';
    public const STATUS_REFUNDED = 'refunded';

}
