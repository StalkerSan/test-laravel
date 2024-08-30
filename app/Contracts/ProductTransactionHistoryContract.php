<?php

namespace App\Contracts;

use App\Contracts\Helpers\HasTimestampContract;

interface ProductTransactionHistoryContract extends HasTimestampContract
{

    public const FIELD_LIST = [
        self::FIELD_ID,
        self::FIELD_USER_ID,
        self::FIELD_PRODUCT_ID,
        self::FIELD_TRANSACTION_TYPE,
        self::FIELD_LOAN_HOURS,
        self::FIELD_LOAN_UNTIL,
        self::FIELD_DATETIME_APPLIED,
        self::FIELD_PRICE
    ];

    public const FIELD_ID = 'id';
    public const FIELD_USER_ID = 'user_id';
    public const FIELD_PRODUCT_ID = 'product_id';
    public const FIELD_TRANSACTION_TYPE = 'transaction_type';
    public const FIELD_LOAN_HOURS = 'loan_hours';
    public const FIELD_DATETIME_APPLIED = 'datetime_applied';
    public const FIELD_LOAN_UNTIL = "loan_until";

    public const FIELD_PRICE = 'price';


    public const AVAILABLE_TRANSACTION_TYPES = [
        self::TRANSACTION_TYPE_EXTEND_LOAN,
        self::TRANSACTION_TYPE_LOAN,
        self::TRANSACTION_TYPE_PURCHASE,
        self::TRANSACTION_TYPE_REFUND,
        self::TRANSACTION_TYPE_RETURN_FROM_LOAN
    ];
    public const TRANSACTION_TYPE_PURCHASE = 'purchase';
    public const TRANSACTION_TYPE_LOAN = 'loan';
    public const TRANSACTION_TYPE_EXTEND_LOAN = 'extend_loan';
    public  const TRANSACTION_TYPE_RETURN_FROM_LOAN = 'return_from_loan';
    public const TRANSACTION_TYPE_REFUND = 'refund';
}
