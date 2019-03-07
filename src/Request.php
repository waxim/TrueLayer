<?php

namespace TrueLayer;

use TrueLayer\Authorize\Token;
use TrueLayer\Exceptions\InvalidCodeExchange;
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
     * @throws InvalidCodeExchange
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     * @return void
     */
    public function __construct(Connection $connection, Token $token)
    {
        if ($token->isExpired()) {
            if (!$token->isRefreshable()) {
                throw new TokenExpiredAndNotRefreshable;
            }

            $token = $connection->refreshOauthToken($token);
        }

        $this->connection = $connection;
        $this->token = $token;
    }
}
