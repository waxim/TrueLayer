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

        $pendingTransactions = new PendingTransactions($connection, $token);

         $pt = $pendingTransactions->get(1);

        $this->assertIsNotBool($pt);
        $this->assertIsArray($pt);
        $this->assertArrayHasKey('transaction_id', $pt[0]);
        $this->assertArrayHasKey('timestamp', $pt[0]);
        $this->assertArrayHasKey('description', $pt[0]);
        $this->assertArrayHasKey('amount', $pt[0]);
        $this->assertArrayHasKey('currency', $pt[0]);
        $this->assertArrayHasKey('transaction_type', $pt[0]);
        $this->assertArrayHasKey('transaction_category', $pt[0]);
        $this->assertArrayHasKey('transaction_classification', $pt[0]);
        $this->assertArrayHasKey('merchant_name', $pt[0]);
        $this->assertArrayHasKey('bank_transaction_id', $pt[0]['meta']);
        $this->assertArrayHasKey('provider_transaction_category', $pt[0]['meta']);
    }
}
