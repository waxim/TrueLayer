<?php

namespace TrueLayer\Bank\Account;

use TrueLayer\Data\Transaction;
use TrueLayer\Exceptions\OauthTokenInvalid;
use TrueLayer\Request;

class PendingTransactions extends Request
{
    /**
     * Get pending transactions
     *
     * @param string $account_id
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws OauthTokenInvalid
     */
    public function get($account_id)
    {
        $result = $this->connection
            ->setAccessToken($this->token->getAccessToken())
            ->get("/data/v1/accounts/" . $account_id . "/transactions/pending");

        $this->statusCheck($result);
        $data = json_decode($result->getBody(), true);

        $transactions = [];

        foreach($data['results'] as $result) {
            $transactions[] = new Transaction($result);
        }

        return $transactions;
    }
}
