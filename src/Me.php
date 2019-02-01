<?php

namespace TrueLayer;

use TrueLayer\Authorize\Token;
use TrueLayer\Connection;
use TrueLayer\Request;

class Me extends Request
{
    /**
     * Get all providers
     * 
     * @return mixed
     */
    public function getMetaData()
    {
        $result = $this->connection
            ->setAccessToken($this->token->getAccessToken())
            ->get("/data/v1/me");

        if((int)$result->getStatusCode() > 400) { 
            throw new OauthTokenInvalid;
        }

        $data = json_decode($result->getBody(), true);
        return $data;
    }
}