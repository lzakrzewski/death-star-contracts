<?php

declare(strict_types=1);

namespace DeathStar\Contracts\Exception;

class InvalidRequestException extends ContractViolationException
{
    public static function missingRequiredHeader(string $header, array $headers): self
    {
        return new self(
            sprintf(
                'Missing required "%s" header in: "%s"',
                $header,
                json_encode($headers)
            )
        );
    }

    public static function invalidContentType(string $expectedContentType, string $contentType): self
    {
        return new self(
            sprintf(
                'Invalid content type "%s" provided, expected: "%s"',
                $contentType,
                $expectedContentType
            )
        );
    }
}
