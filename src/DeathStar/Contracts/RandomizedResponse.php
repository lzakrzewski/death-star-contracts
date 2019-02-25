<?php

declare(strict_types=1);

namespace DeathStar\Contracts;

use DeathStar\Contracts\Exception\NotFoundContractException;

class RandomizedResponse
{
    /** @var ContractsReader */
    private $reader;

    public function __construct(ContractsReader $reader)
    {
        $this->reader = $reader;
    }

    public function getResponseFor(string $method, string $uri, int $statusCode = null): array
    {
        $contract = $this->reader->getContractByMethodAndUri($method, $uri);
        $response = $this->findResponseInContract($method, $uri, $contract, $statusCode);

        if (false === isset($response['content'])) {
            return [];
        }

        $content    = array_values($response['content'])[0];
        $properties = $content['schema']['properties'];

        return $this->randomizeProperties($properties);
    }

    private function findResponseInContract(string $method, string $uri, array $contract, int $statusCode = null): array
    {
        $responses = array_values($contract[strtolower($method)]['responses']);

        foreach ($responses as $contractStatusCode => $response) {
            if (null === $statusCode) {
                return $response;
            }

            if ($statusCode === $contractStatusCode) {
                return $response;
            }
        }

        throw NotFoundContractException::create($method, $uri);
    }

    private function randomizeProperties(array $properties): array
    {
        $result = [];

        foreach ($properties as $propertyName => $property) {
            if ('integer' === $property['type']) {
                $value = rand(1000, 100000);
            } else {
                $value = uniqid();
            }

            $result[$propertyName] = $value;
        }

        return $result;
    }
}
