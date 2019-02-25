<?php

declare(strict_types=1);

namespace DeathStar\Contracts;

use DeathStar\Contracts\Exception\InvalidRequestException;

class RequestValidator
{
    /** @var ContractsReader */
    private $reader;

    public function __construct(ContractsReader $reader)
    {
        $this->reader = $reader;
    }

    public function validateRequest(string $method, string $uri, array $options): void
    {
        $contract = $this->reader->getContractByMethodAndUri($method, $uri);

        $this->hasRequiredHeaders($contract, $method, $options);
        $this->hasValidContentType($contract, $method, $options);
    }

    private function hasRequiredHeaders(array $contract, string $method, array $options): void
    {
        $requiredHeaders = $this->extractRequiredHeaders($contract, $method);
        $headers         = $this->extractHeaders($options);

        foreach ($requiredHeaders as $parameter) {
            if (false === \in_array($parameter, $headers, true)) {
                throw InvalidRequestException::missingRequiredHeader($parameter, $headers);
            }
        }
    }

    private function extractHeaders(array $options): array
    {
        return array_map(
            function (string $header) {
                return strtolower($header);
            },
            array_keys($options['headers'])
        );
    }

    private function extractRequiredHeaders(array $contract, string $method): array
    {
        $authorizationHeaders          = [];
        $requiredHeadersFromParameters = [];

        if (isset($contract['parameters'])) {
            $parameters                    = $contract['parameters'];
            $requiredHeadersFromParameters = array_reduce(
                $parameters,
                function (array $carry, array $parameter) {
                    if ('header' === $parameter['in'] && true === $parameter['required']) {
                        $carry[] = strtolower($parameter['name']);
                    }

                    return $carry;
                },
                []
            );
        }

        if (isset($contract[strtolower($method)]['security'])) {
            $authorizationHeaders[] = 'authorization';
        }

        return array_merge($requiredHeadersFromParameters, $authorizationHeaders);
    }

    private function hasValidContentType(array $contract, string $method, array $options): void
    {
        if (isset($contract[strtolower($method)]['requestBody'])) {
            $expectedContentType = array_keys($contract[strtolower($method)]['requestBody']['content'])[0];
            $extractHeaders      = $this->extractHeaders($options);

            if (false === \in_array('content-type', $extractHeaders, true)) {
                throw InvalidRequestException::missingRequiredHeader('content-type', $extractHeaders);
            }

            $contentType = $options['headers']['content-type'];

            if (\is_array($contentType)) {
                $contentType = $contentType[0];
            }

            if ($expectedContentType !== $contentType) {
                throw InvalidRequestException::invalidContentType($expectedContentType, $contentType);
            }
        }
    }
}
