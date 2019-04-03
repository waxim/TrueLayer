<?php

namespace TrueLayer\Tests\Account;

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Stream\Stream;
use Teapot\StatusCode\Http;
use TrueLayer\Authorize\Token;
use TrueLayer\Bank\Cards;
use TrueLayer\Connection;
use TrueLayer\Data\Card;
use TrueLayer\Tests\TestCase;

class CardsTest extends TestCase
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
            Stream::factory(file_get_contents(__DIR__.'../../../../mocks/data/cards.json'))
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

        $cardsRequest = new Cards($connection, $token);

        $cards = $cardsRequest->get(1);

        $this->assertIsNotBool($cards);
        $this->assertIsArray($cards);

        foreach (array_keys(get_object_vars((new Card()))) as $attribute) {
            $this->assertObjectHasAttribute($attribute, $cards[0]);
        }
    }
}
