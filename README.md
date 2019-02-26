# death-star-contracts [![Build Status](https://travis-ci.org/lzakrzewski/death-star-contracts.svg?branch=master)](https://travis-ci.org/lzakrzewski/death-star-contracts)

The repository contains the set of pre-defined requests and expected responses of [DeathStar API](https://death-star-api.herokuapp.com/).
The [contract-file](src/DeathStar/Contracts/contract.json) has been defined using [OpenAPI 3.0.0](https://github.com/OAI/OpenAPI-Specification/blob/master/versions/3.0.0.md) specification.

#### Installation
`composer require lzakrzewski/death-star-contracts`

#### The contract file
[contract.json](src/DeathStar/Contracts/contract.json)

#### Contract testing
In order to perform "contract testing" you can use built-in components:
- [RequestValidator](src/DeathStar/Contracts/RequestValidator.php) - asserts that provided request is matching contract,
- [RandomizedResponse](src/DeathStar/Contracts/RandomizedResponse.php) - factory to create examples of responses based on the specification.


