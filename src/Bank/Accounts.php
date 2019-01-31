<?php

namespace TrueLayer\Bank;

use TrueLayer\Authorize\Token;
use TrueLayer\Connection;

class Accounts
{
    /**
     * Hold our connection
     * 
     * @var Connection
     */
    protected $connection;

    /**
     * Hold our oauth token
     * 
     * @var Token 
     */
    protected $token;

    /**
     * Start our accounts connection
     * 
     * @param Connection $connection
     * @param Token $token
     * @return void
     */
    public function __construct(Connection $connection, Token $token)
    {
        $this->connection = $connection;
        $this->token = $token;
    }

    /**
     * Get all accounts
     * 
     * @return mixed
     */
    public function getAllAccounts()
    {
        $result = $this->connection
            ->getConnection()
            ->request(
                "GET",
                $this->connection->getUrl("/data/v1/accounts"),
                [
                    'headers' => $this->connection
                        ->getBearer(
                            $this->token->getAccessToken()
                        )
                ]
            );

        if((int)$result->getStatusCode() > 400) { 
            throw new OauthTokenInvalid;
        }

        $accounts = json_decode($result->getBody(), true);
        return $accounts;
    }
}