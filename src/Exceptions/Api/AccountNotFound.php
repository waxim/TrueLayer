<?php

namespace TrueLayer\Exceptions\Api;

use Teapot\StatusCode\Http;

class AccountNotFound extends AbstractApiException
{
    protected $message = 'The requested account cannot be found.';
    protected $code = Http::NOT_FOUND;
}
