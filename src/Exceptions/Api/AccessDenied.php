<?php

namespace TrueLayer\Exceptions\Api;

use Teapot\StatusCode\Http;

class AccessDenied extends AbstractApiException
{
    protected $message = 'Access to the account has been revoked or expired.';
    protected $code = Http::BREW_FORBIDDEN;
}
