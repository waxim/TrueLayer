<?php

namespace TrueLayer\Bank\Card;

use TrueLayer\Data\Card;
use TrueLayer\Exceptions\OauthTokenInvalid;
use TrueLayer\Request;

class Information extends Request
{
    /**
     * Get card information
     *
     * @param string $account_id
     * @return Card
     * @throws OauthTokenInvalid
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function get($account_id)
    {
        $result = $this->connection
            ->setAccessToken($this->token->getAccessToken())
            ->get("/data/v1/cards/" . $account_id);

        $this->OAuthCheck($result);


        $data = json_decode($result->getBody(), true);

        return new Card($data['results']);
    }
}
