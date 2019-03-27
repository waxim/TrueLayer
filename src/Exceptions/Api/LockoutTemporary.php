<?php

namespace TrueLayer\Exceptions\Api;

use Teapot\StatusCode\Http;

class LockoutTemporary extends AbstractApiException
{
    protected $message = 'The account is temporarily locked by the provider.';
    protected $code = Http::BREW_FORBIDDEN;
}
