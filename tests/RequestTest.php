<?php

use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Teapot\StatusCode\Http;
use TrueLayer\Authorize\TokenTest;
use TrueLayer\Exceptions\OauthTokenInvalid;
use TrueLayer\Request;

class RequestTest extends TestCase
{
    private $http;

    public function setUp(): void
    {
        $this->http = new GuzzleHttp\Client(['base_uri' => 'https://127.0.0.1']);
    }

    public function tearDown(): void {
        $this->http = null;
    }

    public function testOauthCheck()
    {
        $request = new Request(ConnectionTest::createTestConnection(), TokenTest::createTestToken());
        $response = new Response(Http::BAD_REQUEST, ['X-Foo' => 'Bar']);
        $this->expectException(
            OauthTokenInvalid::class
        );

        $request->OAuthCheck($response);
    }
}
