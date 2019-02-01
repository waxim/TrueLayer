<?php

namespace TrueLayer\Data; 

use TrueLayer\Data as Model;
use TrueLayer\Data\Bank;
use TrueLayer\Bank\Account\Types;
use DateTime;

class Card extends Model
{
    /**
     * Some constants
     * 
     * @var string 
     */
    const NETWORK_VISA = "VISA";
    const NETWORK_MASTERCARD = "MASTERCARD";
    const NETWORK_AMEX = "AMEX";
    const TYPE_CREDIT = "CREDIT";
    const TYPE_DEBIT = "DEBIT";

    /**
     * Account id
     * 
     * @var string
     */
    public $id;

    /**
     * Card network
     * 
     * @var string
     */
    public $network;

    /**
     * Card type
     * 
     * @var string
     */
    public $type;

    /**
     * Card currency
     * 
     * @var string
     */
    public $currency;

    /**
     * Card name
     * 
     * @var string
     */
    public $name;

    /**
     * Card digits
     * 
     * @var string
     */
    public $last_four_digits;

    /**
     * Name on card
     * 
     * @var string
     */
    public $name_on_card;

    /**
     * Valid from
     * 
     * @var DateTime
     */
    public $valid_from;

    /**
     * Valid to
     * 
     * @var DateTime
     */
    public $valid_to;

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
     */
    public function map(){
        return [
            'id'           => ['key' => 'account_id'],
            'network'      => ['key' => 'card_network'],
            'type'         => ['key' => 'card_type'],
            'currency'     => ['key' => 'currency'],
            'name'         => ['key' => 'display_name'],
            'last_four_digits' => ['key' => 'partial_card_number'],
            'name_on_card' => ['key' => 'name_on_card'],
            'valid_from'   => ['key' => 'valid_from',
                'callback' => function($value) {
                    return new DateTime($value);
                }
            ],
            'valid_to'   => ['key' => 'valid_to',
                'callback' => function($value) {
                    return new DateTime($value);
                }
            ],
            'updated_at'   => ['key' => 'update_timestamp', 
                'callback' => function($value) {
                    return new DateTime($value);
                }
            ],
            'provider'     => [
                'collect' => [
                    'display_name' => 'provider.display_name',
                    'logo_uri'     => 'provider.logo_uri',
                    'provider_id'  =>'provider.provider_id'
                ],
                'callback' => function($values) {
                    return new Bank($values);
                }
            ]
        ];
    }

    /**
     * Is expired?
     * 
     * @return bool
     */
    public function isExpired()
    {
        return $this->valid_to > (new DateTime);
    }

    /**
     * Is MasterCard?
     * 
     * @return bool
     */
    public function isMastercard()
    {
        return $this->network == self::NETWORK_MASTERCARD;
    }

    /**
     * Is Amex?
     * 
     * @return bool
     */
    public function isAmex()
    {
        return $this->network == self::NETWORK_AMEX;
    }

    /**
     * Is Visa?
     * 
     * @return bool
     */
    public function isVisa()
    {
        return $this->network == self::NETWORK_VISA;
    }

    /**
     * Is Credit Card?
     * 
     * @return bool
     */
    public function isCreditCard()
    {
        return $this->type == self::TYPE_CREDIT;
    }

    /**
     * Is Debit Card
     * 
     * @return bool
     */
    public function isDebitCard()
    {
        return $this->type == self::TYPE_DEBIT;
    }
}