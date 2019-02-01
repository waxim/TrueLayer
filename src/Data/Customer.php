<?php

namespace TrueLayer\Data; 

use TrueLayer\Data as Model;
use TrueLayer\Data\Bank;

class Customer extends Model
{
    /**
     * Customer Name
     * 
     * @var string
     */
    public $name;

    /**
     * Customer date of birth
     * 
     * @var DateTime
     */
    public $date_of_birth;

    /**
     * Customer addresses
     * 
     * @var array|Address
     */
    public $addresses = [];

    /**
     * Customer emails
     * 
     * @var array
     */
    public $emails = [];

    /**
     * Customer phone numbers
     * 
     * @var string
     */
    public $phones = [];

    /**
     * Stop dotting
     * 
     * @var bool
     */
    public $should_dot = false;

    /**
     * Provide a map from array
     * to DTO
     * 
     * @var array
     */
    public function map(){
        return [
            'name'         => ['key' => 'full_name'],
            'email'        => ['key' => 'emails'],
            'phones'       => ['key' => 'phones'],
            'addresses'    => ['key' => 'addresses',
                'callback' => function($value){
                    $output = [];
                    if(is_array($value)) {
                        foreach($value as $address) {
                            $output[] = new Address($address);
                        }
                    }
                    return $output;
                }
            ],
            'date_of_birth' => ['key' => 'date_of_birth',
                'callback' => function($value){
                    return DateTime($value);
                }
            ]
        ];
    }
}