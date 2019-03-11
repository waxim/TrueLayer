<?php

namespace TrueLayer\Bank\Card;

use TrueLayer\Data\CardTransaction;
use TrueLayer\Exceptions\OauthTokenInvalid;
use TrueLayer\Request;

class PendingTransactions extends Request
{
    /**
     * Get pending transactions
     *
     * @param string $account_id
     * @return CardTransaction|array
     * @throws OauthTokenInvalid
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function get($account_id)
    {
        $result = $this->connection
            ->setAccessToken($this->token->getAccessToken())
            ->get("/data/v1/cards/" . $account_id . "/transactions/pending");

        $this->OAuthCheck($result);$data = json_decode($result->getBody(), true);
        $results = array_walk($data['results'], function ($value) {
            return new CardTransaction($value);
        });

        return $results;
    }
}
