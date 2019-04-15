<?php

namespace TrueLayer\Tests\Account;

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Stream\Stream;
use TrueLayer\Tests\TestCase;
use Teapot\StatusCode\Http;
use TrueLayer\Authorize\Token;
use TrueLayer\Bank\Account\Transactions;
use TrueLayer\Connection;
use TrueLayer\Data\Transaction;

class TransactionsTest extends TestCase
{
    public function testGetReturnsAnArrayAndNotABoolean()
    {
        $connection = $this->createMock(Connection::class);
        $token = $this->createMock(Token::class);
        $token
            ->method('isExpired')
            ->willReturn(false);

        /** @var Response $response */
        $response = new Response(
            Http::OK,
            ['Example' => 'headers'],
            Stream::factory(file_get_contents(__DIR__.'../../../../mocks/data/transactions.json'))
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

        $transactionsRequest = new Transactions($connection, $token);

        $transactions = $transactionsRequest->get(1);

        $this->assertIsNotBool($transactions);
        $this->assertIsArray($transactions);

        foreach (array_keys(get_object_vars((new Transaction()))) as $attribute) {
            $this->assertObjectHasAttribute($attribute, $transactions[0]);
        }
    }
}
