<?php

namespace TrueLayer\Exceptions\Api;

use Teapot\StatusCode\Http;

class LockoutPermanent extends AbstractApiException
{
    protected $message = 'The account is permanently locked by the provider.';
    protected $code = Http::BREW_FORBIDDEN;
}
