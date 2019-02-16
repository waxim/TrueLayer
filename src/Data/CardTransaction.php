<?php

namespace TrueLayer\Data;

use DateTime;
use TrueLayer\Data as Model;

class CardTransaction extends Model
{
    /**
     * Some constants
     *
     * @var string
     */
    const TYPE_CREDIT = 'CREDIT';
    const TYPE_DEBIT = 'DEBIT';

    /**
     * Transaction id
     *
     * @var string
     */
    public $id;

    /**
     * Transaction description
     *
     * @var string
     */
    public $description;

    /**
     * Transaction type
     *
     * @var string
     */
    public $type;

    /**
     * Transaction category
     *
     * @var string
     */
    public $category;

    /**
     * Transaction amount
     *
     * @var double
     */
    public $amount;

    /**
     * Transaction currency
     *
     * @var string
     */
    public $currency;

    /**
     * Transaction meta
     *
     * @var array
     */
    public $meta;

    /**
     * Transaction timestamp
     *
     * @var DateTime
     */
    public $timestamp;

    /**
     * Skip dotting meta
     *
     * @var mixed
     */
    public $should_dot = ['meta'];

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
            'id' => ['key' => 'transaction_id'],
            'description' => ['key' => 'description'],
            'type' => ['key' => 'transaction_type'],
            'category' => ['key' => 'transaction_category'],
            'amount' => ['key' => 'amount'],
            'currency' => ['key' => 'currency'],
            'meta' => ['key' => 'meta'],
            'timestamp' => ['key' => 'timestamp',
                'callback' => function ($value) {
                    return new DateTime($value);
                }
            ]
        ];
    }


    /**
     * is debit
     *
     * @return bool
     */
    public function isDebit()
    {
        return $this->type == self::TYPE_DEBIT;
    }

    /**
     * is credit
     *
     * @return bool
     */
    public function isCredit()
    {
        return $this->type == self::TYPE_CREDIT;
    }
}