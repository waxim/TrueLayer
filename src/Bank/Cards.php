<?php

namespace TrueLayer\Bank;

use TrueLayer\Authorize\Token;
use TrueLayer\Connection;
use TrueLayer\Request;
use TrueLayer\Data\Card;

class Cards extends Request
{
    /**
     * Get all accounts
     * 
     * @return mixed
     */
    public function get()
    {
       $result = $this->connection
            ->setAccessToken($this->token->getAccessToken())
            ->get("/data/v1/cards");

        if((int)$result->getStatusCode() > 400) { 
            throw new OauthTokenInvalid;
        }

        $accounts = json_decode($result->getBody(), true);
        $results = array_walk($data['results'], function($value) {
            return new Card($value);
        });

        return $results;
    }
}