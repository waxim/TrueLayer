<?php

namespace TrueLayer\Bank\Card;

use TrueLayer\Authorize\Token;
use TrueLayer\Connection;
use TrueLayer\Request;
use TrueLayer\Data\CardTransaction;

class PendingTransactions extends Request
{
    /**
     * Get pending transactions
     * 
     * @param string $account_id
     * @return mixed
     */
    public function get($account_id)
    {
        $result = $this->connection
            ->setAccessToken($this->token->getAccessToken())
            ->get("/data/v1/cards/" . $account_id . "/transactions/pending");

        if((int)$result->getStatusCode() > 400) { 
            throw new OauthTokenInvalid;
        }

        $data = json_decode($result->getBody(), true);
        $results = array_walk($data['results'], function($value) {
            return new CardTransaction($value);
        });

        return $results;
    }
}