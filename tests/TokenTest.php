<?php

namespace TrueLayer\Authorize;

use PHPUnit\Framework\TestCase;

class TokenTest extends TestCase
{
    public static function createTestToken($token = 'ExampleToken', $type = 'token')
    {
        return new Token([
            'access_token' => $token,
            'expires_in' => 100000, // Seconds to expiry
            'token_type' => $type,
        ]);
    }

    public function testCreateTestToken()
    {
        // TODO: Improve this test, it's mainly here to avoid PHPUnits warning
        $token = $this->createTestToken('AnotherToken', 'token');
        $this->assertInstanceOf(Token::class, $token);
    }
}
