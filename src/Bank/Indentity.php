<?php

namespace TrueLayer\Bank;

use TrueLayer\Authorize\Token;
use TrueLayer\Connection;
use TrueLayer\Request;
use TrueLayer\Data\Customer;

class Identity extends Request
{
    /**
     * Get all providers
     * 
     * @return mixed
     */
    public function getIdentity()
    {
        $result = $this->connection
            ->setAccessToken($this->token->getAccessToken())
            ->get("/data/v1/info");

        if((int)$result->getStatusCode() > 400) { 
            throw new OauthTokenInvalid;
        }

        $data = json_decode($result->getBody(), true);
        return new Customer($data['results']);
    }
}