<?php

namespace TrueLayer\Bank;

use TrueLayer\Authorize\Token;
use TrueLayer\Connection;
use TrueLayer\Request;

class Supported extends Request
{
    /**
     * Get all providers
     * 
     * @return mixed
     */
    public function getProviders()
    {
       $result = $this->connection
            ->get("/api/providers");

        $providers = json_decode($result->getBody(), true);
        return $providers;
    }
}