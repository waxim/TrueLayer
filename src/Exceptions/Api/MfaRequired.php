<?php

namespace TrueLayer\Exceptions\Api;

use Teapot\StatusCode\Http;

class MfaRequired extends AbstractApiException
{
    protected $message = 'Multi Factor Authentication required.';
    protected $code = Http::BREW_FORBIDDEN;
}
