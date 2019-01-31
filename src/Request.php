<?php

namespace TrueLayer;

use TrueLayer\Authorize\Token;
use TrueLayer\Connection;

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
     * @return void
     */
    public function __construct(Connection $connection, Token $token)
    {
        $this->connection = $connection;
        $this->token = $token;
    }
}