<?php

namespace TrueLayer\Exceptions\Api;

use Teapot\StatusCode\Http;

class RequestConflict extends AbstractApiException
{
    protected $message = 'This request is already running. Please try again later.';
    protected $code = Http::CONFLICT;
}
