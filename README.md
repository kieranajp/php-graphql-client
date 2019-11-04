# PHP GraphQL Client

[![codecov](https://codecov.io/gh/kieranajp/php-graphql-client/branch/master/graph/badge.svg)](https://codecov.io/gh/kieranajp/php-graphql-client)

This is a PSR-18 compatible GraphQL client, using http://httplug.io.

## Installation

```
$ composer require kieranajp/graphql-client
```

## Usage

Full usage docs are coming, but for now [check the tests](https://github.com/kieranajp/php-graphql-client/blob/master/spec/Client.spec.php).

Pass a httplug compatible adapter to the Client constructor, and away you go!

## Contributing

Please do!

Note that code is checked with `phpcs` and `psalm`, and unit tested with `kahlan`. These are all runnable via the Makefile.

