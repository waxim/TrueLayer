<?php

namespace TrueLayer\Bank\Account;

use TrueLayer\Authorize\Token;
use TrueLayer\Connection;
use TrueLayer\Request;
use TrueLayer\Data\Transaction;
use DateTime;

class Transactions extends Request
{
    /**
     * Get account transactions
     * 
     * @param string $account_id
     * @param DateTime $from
     * @param DateTime $to
     *
     * @return mixed
     */
    public function get($account_id, DateTime $from = null, DateTime $to = null)
    {
        $params = array_filter([
            'from' => ($from ? $from->format(DateTime::DATE_ISO8601) : null),
            'to'   => ($to ? $to->format(DateTime::DATE_ISO8601) : null),
        ]);

        $result = $this->connection
            ->setAccessToken($this->token->getAccessToken())
            ->get("/data/v1/accounts/" . $account_id . "/transactions", $params);

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