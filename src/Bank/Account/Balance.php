<?php

namespace TrueLayer\Bank\Account;

use TrueLayer\Data\Balance as Data;
use TrueLayer\Exceptions\OauthTokenInvalid;
use TrueLayer\Request;

class Balance extends Request
{
    /**
     * Get account balance
     *
     * @param string $account_id
     * @return Data
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws OauthTokenInvalid
     */
    public function get($account_id)
    {
        $result = $this->connection
            ->setAccessToken($this->token->getAccessToken())
            ->get("/data/v1/accounts/" . $account_id . "/balance");

        $this->OAuthCheck($result);
        $data = json_decode($result->getBody(), true);

        return new Data($data["results"]);
    }
}
