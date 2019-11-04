<?php declare(strict_types=1);

namespace Kieranajp\GraphQLClient\Exceptions;

use Psr\Http\Client\ClientExceptionInterface;

/**
 * A ServerException is thrown when a 50x error is encountered while making a GraphQL request.
 * @package Kieranajp\GraphQLClient\Exceptions
 */
class ServerException extends TransportException implements ClientExceptionInterface
{
}
