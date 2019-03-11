<?php

namespace TrueLayer;

use TrueLayer\Exceptions\OauthTokenInvalid;

class Me extends Request
{
    /**
     * Get all providers
     *
     * @return mixed
     * @throws OauthTokenInvalid
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getMetaData()
    {
        $result = $this->connection
            ->setAccessToken($this->token->getAccessToken())
            ->get("/data/v1/me");

        $this->OAuthCheck($result);
        $data = json_decode($result->getBody(), true);

        return $data;
    }
}
