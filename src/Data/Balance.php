<?php

namespace TrueLayer\Data;

use TrueLayer\Data as Model;

class Balance extends Model
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
     * Overdraft
     *
     * @var double
     */
    public $overdraft;

    /**
     * Updated at
     *
     * @var \DateTime
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
            'overdraft' => ['key' => 'overdraft'],
            'updated_at' => ['key' => 'update_timestamp',
                'callback' => function ($value) {
                    return new \DateTime($value);
                }
            ],
        ];
    }

    /**
     * Is the balance overdrawn
     *
     * @return bool
     */
    public function isOverdrawn()
    {
        return $this->current < 0 || (
                $this->overdraft > 0 &&
                ($this->current - $this->overdraft < 0)
            );
    }
}