<?php

namespace TrueLayer\Data;

use DateTime;
use TrueLayer\Bank\Account\Types;
use TrueLayer\Data as Model;

class Account extends Model
{
    /**
     * Account id
     *
     * @var string
     */
    public $id;

    /**
     * Account type
     *
     * @var string
     */
    public $type;

    /**
     * Account number
     *
     * @var string
     */
    public $number;

    /**
     * Account iban
     *
     * @var string
     */
    public $iban;

    /**
     * Account sort code
     *
     * @var string
     */
    public $sort_code;

    /**
     * Account swift
     *
     * @var string
     */
    public $swift_code;

    /**
     * Account currency
     *
     * @var string
     */
    public $currency;

    /**
     * Account name
     *
     * @var string
     */
    public $name;

    /**
     * Updated at
     *
     * @var DateTime
     */
    public $update_at;

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
            'id' => ['key' => 'account_id'],
            'type' => ['key' => 'account_type'],
            'number' => ['key' => 'account_number.number'],
            'iban' => ['key' => 'account_number.iban'],
            'sort_code' => ['key' => 'account_number.sort_code'],
            'swift_code' => ['key' => 'account_number.swift_bic'],
            'currency' => ['key' => 'currency'],
            'name' => ['key' => 'display_name'],
            'updated_at' => ['key' => 'update_timestamp',
                'callback' => function ($value) {
                    return new DateTime($value);
                }
            ],
            'provider' => [
                'collect' => [
                    'display_name' => 'provider.display_name',
                    'logo_uri' => 'provider.logo_uri',
                    'provider_id' => 'provider.provider_id'
                ],
                'callback' => function ($values) {
                    return new Bank($values);
                }
            ]
        ];
    }

    /**
     * Is savings account?
     *
     * @return bool
     */
    public function isSavings()
    {
        return $this->type == Types::SAVINGS || $this->type == Types::BUSINESS_SAVINGS;
    }

    /**
     * Is business account?
     *
     * @return bool
     */
    public function isBusiness()
    {
        return $this->type == Types::BUSINESS_TRANSACTION || $this->type == Types::BUSINESS_SAVINGS;
    }
}