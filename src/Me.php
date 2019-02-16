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

        if ((int)$result->getStatusCode() > 400) {
            throw new OauthTokenInvalid();
        }

        $data = json_decode($result->getBody(), true);
        return $data;
    }
}