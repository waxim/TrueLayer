<?php

namespace TrueLayer\Data;

use TrueLayer\Data as Model;

class Bank extends Model
{
    /**
     * Provider id
     *
     * @var string
     */
    public $id;

    /**
     * Provider logo
     *
     * @var string
     */
    public $logo;

    /**
     * Provider name
     *
     * @var string
     */
    public $name;

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
            'id' => ['key' => 'provider_id'],
            'logo' => ['key' => 'logo_uri'],
            'name' => ['key' => 'display_name'],
        ];
    }
}
