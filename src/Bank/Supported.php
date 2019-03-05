<?php

namespace TrueLayer\Bank;

use TrueLayer\Request;

class Supported extends Request
{
    /**
     * Get all providers
     *
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getProviders()
    {
        $result = $this->connection
            ->get("/api/providers");

        $providers = json_decode($result->getBody(), true);

        return $providers;
    }
}