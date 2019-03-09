<?php

namespace TrueLayer\Bank\Card;

use Teapot\StatusCode\Http;
use TrueLayer\Data\CardBalance;
use TrueLayer\Exceptions\OauthTokenInvalid;
use TrueLayer\Request;

class Balance extends Request
{
    /**
     * Get card balance
     *
     * @param string $account_id
     * @return CardBalance
     * @throws OauthTokenInvalid
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function get($account_id)
    {
        $result = $this->connection
            ->setAccessToken($this->token->getAccessToken())
            ->get("/data/v1/cards/" . $account_id . "/balance");

        if ((int) $result->getStatusCode() > Http::BAD_REQUEST) {
            throw new OauthTokenInvalid();
        }

        $data = json_decode($result->getBody(), true);

        return new CardBalance($data['results']);
    }
}
