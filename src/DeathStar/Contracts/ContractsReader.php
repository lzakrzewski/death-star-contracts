<?php

declare(strict_types=1);

namespace DeathStar\Contracts;

use DeathStar\Contracts\Exception\NotFoundContractException;

class ContractsReader
{
    /** @var array */
    private $contracts;

    public function __construct(string $path)
    {
        $this->contracts = (array) json_decode(file_get_contents($path), true);
    }

    public function getContractByMethodAndUri(string $method, string $uri): array
    {
        if (false === isset($this->contracts['paths'])) {
            throw NotFoundContractException::create($method, $uri);
        }

        $contract = $this->findContract($method, $uri);

        if (false === \in_array(strtolower($method), array_keys($contract), true)) {
            throw NotFoundContractException::create($method, $uri);
        }

        return $contract;
    }

    private function findContract(string $method, string $uri): array
    {
        foreach ($this->contracts['paths'] as $pattern => $contract) {
            $patternParts = $this->explodeUri($pattern);
            $uriParts     = $this->explodeUri($uri);

            if (empty(array_diff($patternParts, $uriParts))) {
                return $contract;
            }
        }

        throw NotFoundContractException::create($method, $uri);
    }

    private function explodeUri(string $uri): array
    {
        return array_values(
            array_filter(
                explode('/', $uri),
                function (string $part) {
                    if (true === empty($part)) {
                        return false;
                    }

                    if (false !== strpos($part, '{')) {
                        return false;
                    }

                    if (false !== strpos($part, '}')) {
                        return false;
                    }

                    return true;
                }
            )
        );
    }
}
