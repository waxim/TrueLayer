<?php

namespace TrueLayer\Tests;

use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Teapot\StatusCode\Http;
use TrueLayer\Exceptions\OauthTokenInvalid;
use TrueLayer\Request;
use TrueLayer\Tests\Traits\HasConnectionTrait;

class RequestTest extends TestCase
{
    use HasConnectionTrait;

    public function testOauthCheck()
    {
        $request = new Request($this->connection, TokenTest::createTestToken());
        $response = new Response(Http::BAD_REQUEST, ['X-Foo' => 'Bar']);
        $this->expectException(
            OauthTokenInvalid::class
        );

        $request->statusCheck($response);
    }
}
