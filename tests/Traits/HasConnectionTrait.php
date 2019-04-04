<?php

namespace TrueLayer\Tests\Traits;

use TrueLayer\Connection;

trait HasConnectionTrait
{
    /**
     * @var Connection
     */
    protected $connection;

    public function setUp(): void
    {
        $this->connection = $this->createTestConnection();
    }

    public static function createTestConnection()
    {
        return new Connection(
            "test_id",
            "test_secret",
            "https://localhost.test"
        );
    }
}
