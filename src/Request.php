<?php

namespace TrueLayer;

use TrueLayer\Authorize\Token;
use TrueLayer\Connection;
use TrueLayer\Exceptions\TokenExpiredAndNotRefreshable;

class Request
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
     * @throws TokenExpiredAndNotRefreshable 
     * @return void
     */
    public function __construct(Connection $connection, Token $token)
    {
        if ($token->isExpired()) { 
            if($token->isRefreshable()) {
                $token = $connection->refreshOauthToken($token);
            } else {
                throw new TokenExpiredAndNotRefreshable;
            }
        }

        $this->connection = $connection;
        $this->token = $token;
    }
}