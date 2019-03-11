<?php

namespace TrueLayer\Bank\Account;

use DateTime;
use TrueLayer\Data\Transaction;
use TrueLayer\Exceptions\OauthTokenInvalid;
use TrueLayer\Request;

class Transactions extends Request
{
    /**
     * Get account transactions
     *
     * @param string $account_id
     * @param DateTime $from
     * @param DateTime $to
     *
     * @return Transaction|array
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws OauthTokenInvalid
     */
    public function get($account_id, DateTime $from = null, DateTime $to = null)
    {
        $params = array_filter([
            'from' => ($from ? $from->format(DateTime::ISO8601) : null),
            'to' => ($to ? $to->format(DateTime::ISO8601) : null),
        ]);

        $result = $this->connection
            ->setAccessToken($this->token->getAccessToken())
            ->get("/data/v1/accounts/" . $account_id . "/transactions", $params);

        $this->OAuthCheck($result);
        $data = json_decode($result->getBody(), true);
        $results = array_walk($data['results'], function ($value) {
            return new Transaction($value);
        });

        return $results;
    }
}
