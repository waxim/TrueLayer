<?php

namespace TrueLayer\Exceptions;

class InvalidCodeExchange  extends \Exception
{
    /**
     * Our error message
     * 
     * @var string
     */
    protected $message = "Sorry, we could not fetch a token from that code.";
}