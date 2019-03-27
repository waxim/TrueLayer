<?php

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Stream\Stream;
use PHPUnit\Framework\TestCase;
use Teapot\StatusCode\Http;
use TrueLayer\Authorize\Token;
use TrueLayer\Bank\Account\Information;
use TrueLayer\Connection;
use TrueLayer\Data\Account as Data;

class InformationTest extends TestCase
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
            Stream::factory(file_get_contents(__DIR__.'../../../../mocks/data/information.json'))
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

        $informationRequest = new Information($connection, $token);

        $information = $informationRequest->get(1);

        $this->assertIsNotBool($information);
        $this->assertIsNotArray($information);
        $this->assertInstanceOf(Data::class, $information);
    }
}
