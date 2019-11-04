<?php declare(strict_types=1);

namespace Kieranajp\GraphQLClient;

use Http\Message\RequestFactory;
use Kieranajp\GraphQLClient\Exceptions\ClientException;
use Kieranajp\GraphQLClient\Exceptions\ServerException;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * This Client facilitates making requests to GraphQL APIs.
 * @package Kieranajp\GraphQLClient
 */
class Client implements ClientInterface
{
    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var RequestFactory
     */
    private $requestFactory;

    /**
     * @var array
     */
    private $headers = [];

    /**
     * Client constructor.
     * @param ClientInterface $client
     * @param RequestFactory $requestFactory
     */
    public function __construct(ClientInterface $client, RequestFactory $requestFactory)
    {
        $this->client = $client;
        $this->requestFactory = $requestFactory;
    }

    /**
     * Set global headers to be sent with every GraphQL request.
     *
     * @param array $headers
     * @return ClientInterface
     */
    public function setHeaders(array $headers): ClientInterface
    {
        $this->headers = $headers;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        return $this->client->sendRequest($request);
    }

    /**
     * Send a GraphQL query.
     *
     * @param string $query
     * @param array|null $variables
     * @return ResponseInterface
     * @throws ClientExceptionInterface
     */
    public function query(string $query, ?array $variables = null): ResponseInterface
    {
        $body = compact('query');
        if (! is_null($variables)) {
            $body['variables'] = $variables;
        }

        $request = $this->requestFactory->createRequest(
            'POST',
            '',
            $this->headers,
            json_encode($body)
        );

        $response = $this->sendRequest($request);

        if (HttpStatusCode::isServerError($response->getStatusCode())) {
            throw new ServerException($request, $response);
        }

        if (HttpStatusCode::isClientError($response->getStatusCode())) {
            throw new ClientException($request, $response);
        }

        return $response;
    }
}
