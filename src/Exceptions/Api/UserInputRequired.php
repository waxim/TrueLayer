<?php

namespace TrueLayer\Exceptions\Api;

use Teapot\StatusCode\Http;

class UserInputRequired extends AbstractApiException
{
    protected $message = 'User input is required by the provider.';
    protected $code = Http::BREW_FORBIDDEN;
}
