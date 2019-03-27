<?php

namespace TrueLayer\Exceptions\Api;

use Teapot\StatusCode\Http;

class EndPointNotSupported extends AbstractApiException
{
    protected $message = 'Feature not supported by the provider.';
    protected $code = Http::NOT_IMPLEMENTED;
}
