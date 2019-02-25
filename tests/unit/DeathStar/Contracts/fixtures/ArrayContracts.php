<?php

declare(strict_types=1);

namespace tests\unit\DeathStar\Contracts\fixtures;

class ArrayContracts
{
    public static function delete(): array
    {
        return [
            'parameters' => [
                    0 => [
                            'name'     => 'exhaustId',
                            'in'       => 'path',
                            'required' => true,
                            'schema'   => [
                                    'type' => 'integer',
                                ],
                        ],
                    1 => [
                            'name'     => 'x-torpedoes',
                            'in'       => 'header',
                            'required' => true,
                            'schema'   => [
                                    'type' => 'integer',
                                ],
                        ],
                ],
            'delete' => [
                    'security' => [
                            0 => [
                                    'bearerAuth' => [
                                        ],
                                ],
                        ],
                    'responses' => [
                            204 => [
                                    'description' => 'empty response',
                                ],
                        ],
                ],
        ];
    }

    public static function get(): array
    {
        return [
            'parameters' => [
                0 => [
                        'name'     => 'prisonerId',
                        'in'       => 'path',
                        'required' => true,
                        'schema'   => [
                                'type' => 'integer',
                            ],
                    ],
            ],
            'get' => [
                    'security' => [
                            0 => [
                                    'bearerAuth' => [
                                        ],
                                ],
                        ],
                    'responses' => [
                            200 => [
                                    'description' => 'prisoner',
                                    'content'     => [
                                            'application/json' => [
                                                    'schema' => [
                                                            'type'       => 'object',
                                                            'properties' => [
                                                                    'name' => [
                                                                            'type' => 'string',
                                                                        ],
                                                                ],
                                                        ],
                                                ],
                                        ],
                                ],
                        ],
                ],
        ];
    }

    public static function post(): array
    {
        return [
            'post' => [
                    'requestBody' => [
                            'content' => [
                                    'application/x-www-form-urlencoded' => [
                                            'schema' => [
                                                    'type'       => 'object',
                                                    'properties' => [
                                                            'grant_type' => [
                                                                    'type' => 'string',
                                                                ],
                                                            'client_id' => [
                                                                    'type' => 'string',
                                                                ],
                                                            'client_secret' => [
                                                                    'type' => 'string',
                                                                ],
                                                        ],
                                                ],
                                        ],
                                ],
                        ],
                    'responses' => [
                            201 => [
                                    'description' => 'Created token',
                                    'content'     => [
                                            'application/json' => [
                                                    'schema' => [
                                                            'type'       => 'object',
                                                            'properties' => [
                                                                    'access_token' => [
                                                                            'type' => 'string',
                                                                        ],
                                                                    'expires_in' => [
                                                                            'type' => 'integer',
                                                                        ],
                                                                    'token_type' => [
                                                                            'type' => 'string',
                                                                        ],
                                                                    'scope' => [
                                                                            'type' => 'string',
                                                                        ],
                                                                ],
                                                        ],
                                                ],
                                        ],
                                ],
                        ],
                ],
        ];
    }
}
