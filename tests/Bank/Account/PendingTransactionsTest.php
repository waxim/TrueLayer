<?php

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Stream\Stream;
use PHPUnit\Framework\TestCase;
use Teapot\StatusCode\Http;
use TrueLayer\Authorize\Token;
use TrueLayer\Bank\Account\PendingTransactions;
use TrueLayer\Connection;

class PendingTransactionsTest extends TestCase
{
    public function testGetReturnsAnArrayAndNotABoolean()
    {
        $connection = $this->createMock(Connection::class);
        $token = $this->createMock(Token::class);
        $token
            ->method('isExpired')
            ->willReturn(false);

        $response = new Response(
            Http::OK,
            ['Example' => 'headers'],
            Stream::factory(file_get_contents(__DIR__.'../../../../mocks/pending-transactions.json'))
        );

        $connection
            ->method('getOauthToken')
            ->willReturn($token);

        $connection
            ->method('setAccessToken')
            ->willReturn($connection);

        $connection
            ->method('get')
            ->willReturn($response);

        $pt = new PendingTransactions($connection, $token);

        $this->assertIsNotBool(
            $pt->get(12)
        );

        $this->assertIsArray(
            $pt->get(34)
        );
    }
}
