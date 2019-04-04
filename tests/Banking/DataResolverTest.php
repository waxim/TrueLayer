<?php

namespace TrueLayer\Tests\Banking;

use TrueLayer\Banking\DataResolver;
use TrueLayer\Data\Status;
use TrueLayer\Exceptions\UnresolvableResult;
use TrueLayer\Tests\TestCase;
use TrueLayer\Tests\Traits\HasConnectionTrait;

class DataResolverTest extends TestCase
{
    use HasConnectionTrait;

    public function testWhenResolverFunctionDoesntExist()
    {
        $this->expectException(UnresolvableResult::class);
        $this->connection->resolver(['results' => []], 'aFunctionWhichDoesNotExist');
    }

    public function testBankingResolver()
    {
        $resolver = new DataResolver();

        $mockData = json_decode($this->getMockResponse('status/availability.json'), true);
        $providers = $mockData['results'][0]['providers'];
        $monzoMockData = $providers[0];
        $starlingMockData = $providers[1];

        $this->assertEquals('oauth-monzo', $monzoMockData['provider_id']);
        $this->assertEquals('oauth-starling', $starlingMockData['provider_id']);

        $this->connection->setDataResolver($resolver);
        $resolver = $this->connection->resolver($mockData, 'getAvailability');

        /** @var Status $monzoStatus */
        $monzoStatus = $resolver[$monzoMockData['provider_id']];
        /** @var Status $starlingStatus */
        $starlingStatus = $resolver[$starlingMockData['provider_id']];

        $this->assertInstanceOf(Status::class, $monzoStatus);
        $this->assertInstanceOf(Status::class, $starlingStatus);

        $this->assertIsNumeric($starlingStatus->accounts);
        $this->assertIsNumeric($monzoStatus->accounts);

        // TODO: Write a test to sum the availability of multiple providers
        // https://github.com/waxim/TrueLayer/issues/31
        // $this->assertSame($monzo['endpoints'][1]['availability'], $monzoStatus->accounts);
        // $this->assertSame($starling['endpoints'][1]['availability'], $starlingStatus->accounts);
    }
}
