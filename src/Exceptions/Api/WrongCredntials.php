<?php

namespace TrueLayer\Exceptions\Api;

use Teapot\StatusCode\Http;

class WrongCredntials extends AbstractApiException
{
    protected $message = 'The supplied credentials are invalid.';
    protected $code = Http::UNAUTHORIZED;
}
