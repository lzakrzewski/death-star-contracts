<?php

declare(strict_types=1);

namespace tests\unit\DeathStar\Contracts;

use DeathStar\Contracts\ContractsReader;
use DeathStar\Contracts\Exception\InvalidRequestException;
use DeathStar\Contracts\RequestValidator;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use tests\unit\DeathStar\Contracts\fixtures\ArrayContracts;

class RequestValidatorTest extends TestCase
{
    /** @var ContractsReader|ObjectProphecy */
    private $reader;

    /** @var RequestValidator */
    private $requestValidator;

    /** @test */
    public function it_can_validate_request(): void
    {
        $this->reader->getContractByMethodAndUri('GET', '/prisoner/lea')
            ->willReturn(ArrayContracts::get());

        $this->requestValidator->validateRequest(
            'GET',
            '/prisoner/lea',
            [
                'headers' => [
                    'authorization' => 'Bearer some-token',
                    'content-type'  => 'application/json',
                ],
            ]
        );

        $this->assertTrue(true);
    }

    /** @test */
    public function it_fails_when_request_does_not_have_x_torpedoes_required_header(): void
    {
        $this->reader->getContractByMethodAndUri('DELETE', '/reactor/exhaust/1')
            ->willReturn(ArrayContracts::delete());

        $this->expectException(InvalidRequestException::class);

        $this->requestValidator->validateRequest(
            'DELETE',
            '/reactor/exhaust/1',
            [
                'headers' => [
                    'authorization' => 'Bearer some-token',
                    'content-type'  => 'application/json',
                ],
            ]
        );
    }

    /** @test */
    public function it_fails_when_request_does_not_have_required_authorization(): void
    {
        $this->reader->getContractByMethodAndUri('DELETE', '/reactor/exhaust/1')
            ->willReturn(ArrayContracts::delete());

        $this->expectException(InvalidRequestException::class);

        $this->requestValidator->validateRequest(
            'DELETE',
            '/reactor/exhaust/1',
            [
                'headers' => [
                    'content-type' => 'application/json',
                    'x-torpedoes'  => '1',
                ],
            ]
        );
    }

    /** @test */
    public function it_fails_when_request_has_invalid_content_type(): void
    {
        $this->reader->getContractByMethodAndUri('POST', '/Token')
            ->willReturn(ArrayContracts::post());

        $this->expectException(InvalidRequestException::class);

        $this->requestValidator->validateRequest(
            'POST',
            '/Token',
            [
                'headers' => [
                    'content-type' => 'application/json',
                ],
                'body' => [
                    'grant_type'    => 'client_credentials',
                    'client_id'     => 'x',
                    'client_secret' => 'y',
                ],
            ]
        );
    }

    protected function setUp(): void
    {
        $this->reader           = $this->prophesize(ContractsReader::class);
        $this->requestValidator = new RequestValidator($this->reader->reveal());
    }
}
