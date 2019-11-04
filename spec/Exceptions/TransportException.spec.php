<?php declare(strict_types=1);

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Kieranajp\GraphQLClient\Exceptions\ClientException;
use Kieranajp\GraphQLClient\Exceptions\ServerException;
use Kieranajp\GraphQLClient\Exceptions\TransportException;
use Kieranajp\GraphQLClient\HttpStatusCode;

describe(TransportException::class, function () {
    it('contains the sent request', function () {
        $request = new Request('POST', '');
        $exception = new ClientException($request, new Response());

        expect($exception->getRequest())->toEqual($request);
    });

    it('contains the received response', function () {
        $request = new Request('POST', '');
        $response = new Response(HttpStatusCode::BAD_GATEWAY);
        $exception = new ServerException($request, $response);

        expect($exception->getResponse())->toEqual($response);
        expect($exception->getMessage())->toContain('BAD_GATEWAY');
    });
});
