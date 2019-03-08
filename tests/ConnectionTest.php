<?php

use PHPUnit\Framework\TestCase;

class ConnectionTest extends TestCase
{
    public function testWeCanSetClientIdAndSecret()
    {
        $connection = (new \TrueLayer\Connection(
            "test_id",
            "test_secret",
            "https://localhost.test"
        ));

        $this->assertSame("test_id", $connection->getClientId());
        $this->assertSame("test_secret", $connection->getClientSecret());
        $this->assertSame("https://localhost.test", $connection->getRequestUri());
    }

    public function testWeGetAnAuthorizationLink()
    {
        $connection = (new \TrueLayer\Connection(
            "test_id",
            "test_secret",
            "https://localhost.test"
        ));

        $url = filter_var($connection->getAuthorizationLink(), FILTER_VALIDATE_URL);
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
}
