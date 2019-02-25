<?php

declare(strict_types=1);

namespace tests\unit\DeathStar\Contracts;

use DeathStar\Contracts\ContractsReader;
use DeathStar\Contracts\Exception\NotFoundContractException;
use PHPUnit\Framework\TestCase;

class ContractsReaderTest extends TestCase
{
    /** @var ContractsReader */
    private $contractsReader;

    /** @test */
    public function it_can_get_contract_by_request_method_and_uri(): void
    {
        $contract = $this->contractsReader->getContractByMethodAndUri('DeLeTe', '/reactor/exhaust/1');

        $this->assertNotEmpty($contract);
    }

    /**
     * @test
     * @dataProvider invalidRequestArguments
     *
     * @param string $method
     * @param string $uri
     *
     * @throws NotFoundContractException
     */
    public function it_can_not_get_contract_when_it_does_not_exist(string $method, string $uri): void
    {
        $this->expectException(NotFoundContractException::class);

        $this->contractsReader->getContractByMethodAndUri($method, $uri);
    }

    public function invalidRequestArguments(): array
    {
        return [
            ['GET', '/reactor/exhaust/1'],
            ['DELETE', '/prisoner/123'],
            ['Put', '/Token'],
        ];
    }

    protected function setUp(): void
    {
        $this->contractsReader = new ContractsReader(__DIR__.'/../../../../src/DeathStar/Contracts/contract.json');
    }
}
