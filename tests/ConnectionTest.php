<?php

use PHPUnit\Framework\TestCase;
use TrueLayer\Connection;

class ConnectionTest extends TestCase
{
    private $connection;

    public function setUp(): void
    {
        $this->connection = $this->createTestConnection();
    }

    public function testWeCanSetClientIdAndSecret()
    {
        $this->assertSame("test_id", $this->connection->getClientId());
        $this->assertSame("test_secret", $this->connection->getClientSecret());
        $this->assertSame("https://localhost.test", $this->connection->getRequestUri());
    }

    public function testWeGetAnAuthorizationLink()
    {

        $url = filter_var($this->connection->getAuthorizationLink(), FILTER_VALIDATE_URL);
        $this->assertTrue((bool) $url);
        $this->assertStringContainsString('response_type', $url);
        $this->assertStringContainsString('client_id', $url);
        $this->assertStringContainsString('test_id', $url);
        $this->assertStringContainsString('nonce', $url);
        $this->assertStringContainsString('scope', $url);
        $this->assertStringContainsString('redirect_uri', $url);
        $this->assertStringContainsString('enable_mock', $url);
        $this->assertStringContainsString('enable_oauth_providers', $url);
        $this->assertStringContainsString('enable_open_banking_providers', $url);
        $this->assertStringContainsString('enable_credentials_sharing_providers', $url);
        $this->assertStringContainsString('response_mode', $url);
    }

    public static function createTestConnection(): Connection
    {
        return new Connection(
            "test_id",
            "test_secret",
            "https://localhost.test"
        );
    }
}
