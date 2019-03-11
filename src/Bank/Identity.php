<?php

namespace TrueLayer\Bank;

use TrueLayer\Data\Customer;
use TrueLayer\Exceptions\OauthTokenInvalid;
use TrueLayer\Request;

class Identity extends Request
{
    /**
     * Get all providers
     *
     * @return mixed
     * @throws OauthTokenInvalid
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getIdentity()
    {
        $result = $this->connection
            ->setAccessToken($this->token->getAccessToken())
            ->get("/data/v1/info");

        $this->OAuthCheck($result);
        $data = json_decode($result->getBody(), true);
        return new Customer($data['results']);
    }
}
