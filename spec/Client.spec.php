<?php declare(strict_types=1);

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Http\Message\MessageFactory\GuzzleMessageFactory;
use Http\Message\RequestFactory;
use Http\Mock\Client as MockClient;
use Kieranajp\GraphQLClient\Client;
use Kieranajp\GraphQLClient\Exceptions\ClientException;
use Kieranajp\GraphQLClient\Exceptions\ServerException;
use Kieranajp\GraphQLClient\HttpStatusCode;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

describe(Client::class, function () {
    given('requestFactory', function (): RequestFactory {
        return new GuzzleMessageFactory();
    });

    given('psr18', function (): ClientInterface {
        return new MockClient($this->requestFactory);
    });

    given('query', function (): string {
        return <<<GRAPHQL
            query GetPerson(\$id: ID!) {
                person(id: \$id) {
                    name
                    age
                    bio {
                        ...BioFragment
                    }
                }
            }
            
            fragment BioFragment on Bio {
                text
                updatedAt
            }
GRAPHQL;
    });

    it('is instantiable given a PSR-18 client', function () {
        $client = new Client($this->psr18, $this->requestFactory);

        expect($client)->toBeAnInstanceOf(Client::class);
        expect($client)->toBeAnInstanceOf(ClientInterface::class);
    });

    it('proxies requests to the provided PSR-18 client', function () {
        $request = new Request('GET', 'https://example.org/123');
        expect($this->psr18)->toReceive('sendRequest')->with($request);

        $client = new Client($this->psr18, $this->requestFactory);
        $client->sendRequest($request);
    });

    it('sends GraphQL requests', function () {
        $client = new Client($this->psr18, $this->requestFactory);

        $result = $client->query($this->query);
        expect($result)->toBeAnInstanceOf(ResponseInterface::class);
    });

    it('throws a ClientException when a 40x status code is encountered', function () {
        $client = new Client($this->psr18, $this->requestFactory);
        $response = new Response(HttpStatusCode::NOT_FOUND);

        allow($this->psr18)->toReceive('sendRequest')->andReturn($response);

        $run = function () use ($client) {
            $client->query($this->query, ['id' => 123]);
        };

        expect($run)->toThrow(new ClientException(new Request('POST', ''), $response));
    });

    it('throws a ServerException when a 50x status code is encountered', function () {
        $client = new Client($this->psr18, $this->requestFactory);
        $response = new Response(HttpStatusCode::BAD_GATEWAY);

        allow($this->psr18)->toReceive('sendRequest')->andReturn($response);

        $run = function () use ($client) {
            $client->query($this->query, ['id' => 123]);
        };

        expect($run)->toThrow(new ServerException(new Request('POST', ''), $response));
    });

    it('forwards headers to the embedded client', function () {
        $client = new Client($this->psr18, $this->requestFactory);
        $client->setHeaders(['Authorization' => 'Bearer 123']);
        $client->query($this->query);

        /** @var RequestInterface $sentRequest */
        $sentRequest = $this->psr18->getRequests()[0];

        expect($sentRequest->getHeaderLine('Authorization'))->toEqual('Bearer 123');
    });
});
