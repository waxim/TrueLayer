<?php

namespace TrueLayer\Exceptions;

class TokenExpiredAndNotRefreshable extends \Exception
{
    /**
     * Our error message
     *
     * @var string
     */
    protected $message = "Sorry, that token could not be refreshed.";
}
