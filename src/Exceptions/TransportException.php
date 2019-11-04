<?php declare(strict_types=1);

namespace Kieranajp\GraphQLClient\Exceptions;

use Kieranajp\GraphQLClient\HttpStatusCode;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;

/**
 * A TransferException is thrown when an error is encountered while making a GraphQL request.
 * @package Kieranajp\GraphQLClient\Exceptions
 */
abstract class TransportException extends RuntimeException implements ClientExceptionInterface
{
    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var ResponseInterface|null
     */
    private $response;

    /**
     * TransportException constructor.
     * @param RequestInterface $request
     * @param ResponseInterface|null $response
     */
    public function __construct(RequestInterface $request, ?ResponseInterface $response)
    {
        $this->request = $request;
        $this->response = $response;

        $message = sprintf(
            'A `%s` response was encountered when querying the GraphQL server.',
            HttpStatusCode::getName($response->getStatusCode()),
            $request->getMethod(),
            $request->getUri()
        );

        parent::__construct($message, $response->getStatusCode());
    }

    /**
     * @return RequestInterface
     */
    public function getRequest(): RequestInterface
    {
        return $this->request;
    }

    /**
     * @return ResponseInterface|null
     */
    public function getResponse(): ?ResponseInterface
    {
        return $this->response;
    }
}
