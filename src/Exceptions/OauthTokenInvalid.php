<?php

namespace TrueLayer\Exceptions;

class OauthTokenInvalid extends \Exception
{
    /**
     * Our error message
     *
     * @var string
     */
    protected $message = "Sorry, your Oauth token is invalid";
}