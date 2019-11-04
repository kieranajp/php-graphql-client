<?php declare(strict_types=1);

namespace Kieranajp\GraphQLClient\Exceptions;

use Psr\Http\Client\ClientExceptionInterface;

/**
 * A ClientException is thrown when a 40x error is encountered while making a GraphQL request.
 * @package Kieranajp\GraphQLClient\Exceptions
 */
class ClientException extends TransportException implements ClientExceptionInterface
{
}
