<?php

namespace TrueLayer\Data;

use TrueLayer\Data as Model;

class Address extends Model
{
    /**
     * Full address
     *
     * @var string
     */
    public $full;

    /**
     * Address city
     *
     * @var string
     */
    public $city;

    /**
     * Address state
     *
     * @var string
     */
    public $state;

    /**
     * Address zip
     *
     * @var string
     */
    public $zip;

    /**
     * Address country
     *
     * @var string
     */
    public $country;

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
            'full' => ['key' => 'address'],
            'city' => ['key' => 'city'],
            'state' => ['key' => 'state'],
            'zip' => ['key' => 'zip'],
            'country' => ['key' => 'country'],
        ];
    }

    /**
     * Alias for zip
     *
     * @return string
     */
    public function getPostCode()
    {
        return $this->zip;
    }

    /**
     * Alias for county
     *
     * @return string
     */
    public function getCounty()
    {
        return $this->state;
    }
}
