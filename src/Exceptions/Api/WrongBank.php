<?php

namespace TrueLayer\Exceptions\Api;

use Teapot\StatusCode\Http;

class WrongBank extends AbstractApiException
{
    protected $message = 'The selected provider recognises the user within a different context.';
    protected $code = Http::GONE;
}
