<?php

namespace TrueLayer\Tests\Account;

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Stream\Stream;
use TrueLayer\Tests\TestCase;
use Teapot\StatusCode\Http;
use TrueLayer\Authorize\Token;
use TrueLayer\Bank\Account\PendingTransactions;
use TrueLayer\Connection;
use TrueLayer\Data\Transaction;

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
        $this->assertInstanceOf(Transaction::class, $pt[0]);

        $attributes = [
            'id',
            'timestamp',
            'description',
            'amount',
            'currency',
            'type',
            'category',
            'classification',
            'merchant_name',
            'meta',
            'category',
        ];

        foreach ($attributes as $attribute) {
            $this->assertObjectHasAttribute($attribute, $pt[0]);
        }
    }
}
