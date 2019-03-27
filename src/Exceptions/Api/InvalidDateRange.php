<?php

namespace TrueLayer\Exceptions\Api;

use Teapot\StatusCode\Http;

class InvalidDateRange extends AbstractApiException
{
    protected $message = 'The supplied date range is not valid.';
    protected $code = Http::BAD_REQUEST;
}
