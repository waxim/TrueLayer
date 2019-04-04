<?php

namespace TrueLayer\Tests\Traits;

use TrueLayer\Connection;

trait HasConnectionTrait
{
    /**
     * @var Connection
     */
    protected $connection;

    protected function setUp(): void
    {
        $this->connection = $this->createTestConnection();
    }

    protected function createTestConnection()
    {
        return new Connection(
            "test_id",
            "test_secret",
            "https://localhost.test"
        );
    }
}
