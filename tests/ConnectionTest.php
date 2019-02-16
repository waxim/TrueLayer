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
        $this->assertTrue((bool)$url);
    }
}