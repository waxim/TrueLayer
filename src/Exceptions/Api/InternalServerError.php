<?php

namespace TrueLayer\Exceptions\Api;

use Teapot\StatusCode\Http;

class InternalServerError extends AbstractApiException
{
    protected $message = 'Internal Server Error.';
    protected $code = Http::INTERNAL_SERVER_ERROR;
}
