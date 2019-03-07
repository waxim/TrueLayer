<?php

namespace TrueLayer\Data;

use DateTime;
use TrueLayer\Data as Model;

class CardBalance extends Model
{
    /**
     * Available balance
     *
     * @var double
     */
    public $available;

    /**
     * Currency
     *
     * @var string
     */
    public $currency;

    /**
     * current
     *
     * @var double
     */
    public $current;

    /**
     * Credit limit
     *
     * @var double
     */
    public $credit_limit;

    /**
     * Last statement balance
     *
     * @var double
     */
    public $last_statement_balance;

    /**
     * Last statement date
     *
     * @var DateTime
     */
    public $last_statement_date;

    /**
     * Payment due
     *
     * @var double
     */
    public $payment_due;

    /**
     * Payment due date
     *
     * @var DateTime
     */
    public $payment_due_date;

    /**
     * Updated at
     *
     * @var DateTime
     */
    public $updated_at;

    /**
     * Provide a map from array
     * to DTO
     *
     * @var array
     * @return array
     */
    public function map()
    {
        return [
            'available' => ['key' => 'available'],
            'currency' => ['key' => 'currency'],
            'current' => ['key' => 'current'],
            'credit_limit' => ['key' => 'credit_limit'],
            'last_statement_balance' => ['key' => 'last_statement_balance'],
            'payment_due' => ['key' => 'payment_due'],
            'payment_due_date' => ['key' => 'payment_due_date',
                'callback' => function ($value) {
                    return new DateTime($value);
                }
            ],
            'last_statement_date' => ['key' => 'last_statement_date',
                'callback' => function ($value) {
                    return new DateTime($value);
                }
            ],
            'updated_at' => ['key' => 'update_timestamp',
                'callback' => function ($value) {
                    return new DateTime($value);
                }
            ],
        ];
    }
}
