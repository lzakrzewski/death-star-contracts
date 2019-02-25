<?php

declare(strict_types=1);

namespace DeathStar\Contracts\Exception;

class NotFoundContractException extends ContractViolationException
{
    public static function create(string $method, string $uri): self
    {
        return new self(
            sprintf('The contract with request method "%s" and "%s" does not exist', $method, $uri)
        );
    }
}
