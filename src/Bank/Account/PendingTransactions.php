<?php

namespace TrueLayer\Bank\Account;

use TrueLayer\Authorize\Token;
use TrueLayer\Connection;
use TrueLayer\Request;
use TrueLayer\Data\Transaction;

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
            ->get("/data/v1/accounts/" . $account_id . "/transactions/pending");

        if((int)$result->getStatusCode() > 400) { 
            throw new OauthTokenInvalid;
        }

        $data = json_decode($result->getBody(), true);
        $results = array_walk($data['results'], function($value) {
            return new Transaction($value);
        });

        return $results;
    }
}