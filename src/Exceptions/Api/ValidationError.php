<?php

namespace TrueLayer\Exceptions\Api;

use Teapot\StatusCode\Http;

class ValidationError extends AbstractApiException
{
    protected $message = 'The supplied parameters are not valid.';
    protected $code = Http::BAD_REQUEST;
}
