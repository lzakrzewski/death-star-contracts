<?php

declare(strict_types=1);

namespace tests\unit\DeathStar\Contracts;

use DeathStar\Contracts\ContractsReader;
use DeathStar\Contracts\Exception\NotFoundContractException;
use DeathStar\Contracts\RandomizedResponse;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use tests\unit\DeathStar\Contracts\fixtures\ArrayContracts;

class RandomizedResponseTest extends TestCase
{
    /** @var ContractsReader|ObjectProphecy */
    private $reader;

    /** @var RandomizedResponse */
    private $randomizedResponse;

    /** @test */
    public function it_can_return_randomized_response(): void
    {
        $this->reader->getContractByMethodAndUri('POST', '/Token')
            ->willReturn(ArrayContracts::post());

        $response = $this->randomizedResponse->getResponseFor('POST', '/Token');

        $this->assertArrayHasKey('access_token', $response);
        $this->assertArrayHasKey('expires_in', $response);
    }

    /** @test */
    public function it_returns_empty_array_when_no_response(): void
    {
        $this->reader->getContractByMethodAndUri('DELETE', '/reactor/exhaust/1')
            ->willReturn(ArrayContracts::delete());

        $response = $this->randomizedResponse->getResponseFor('DELETE', '/reactor/exhaust/1');

        $this->assertEmpty($response);
    }

    /** @test */
    public function it_fails_when_can_not_find_response_with_expected_status_code(): void
    {
        $this->expectException(NotFoundContractException::class);

        $this->reader->getContractByMethodAndUri('GET', '/prisoner/lea')
            ->willReturn(ArrayContracts::get());

        $this->randomizedResponse->getResponseFor('GET', '/prisoner/lea', 405);
    }

    protected function setUp(): void
    {
        $this->reader             = $this->prophesize(ContractsReader::class);
        $this->randomizedResponse = new RandomizedResponse($this->reader->reveal());
    }
}
