<?php declare(strict_types=1);

use Kieranajp\GraphQLClient\HttpStatusCode;

describe(HttpStatusCode::class, function () {
    it('contains constants for common HTTP status codes', function () {
        expect(HttpStatusCode::BAD_REQUEST)->toEqual(400);
        expect(HttpStatusCode::INTERNAL_SERVER_ERROR)->toEqual(500);
    });

    it('can convert status codes into text representations', function () {
        expect(HttpStatusCode::getName(200))->toEqual('OK');
        expect(HttpStatusCode::getName(400))->toEqual('BAD_REQUEST');
        expect(HttpStatusCode::getName(502))->toEqual('BAD_GATEWAY');
        expect(HttpStatusCode::getName(301))->toEqual('MOVED_PERMANENTLY');
    });

    it('does not crash when presented with an unknown code', function () {
        expect(HttpStatusCode::getName(999))->toEqual('UNKNOWN');
    });

    it('can distinguish error codes', function () {
        expect(HttpStatusCode::isError(100))->toBeFalsy();
        expect(HttpStatusCode::isError(200))->toBeFalsy();
        expect(HttpStatusCode::isError(301))->toBeFalsy();
        expect(HttpStatusCode::isError(400))->toBeTruthy();
        expect(HttpStatusCode::isError(500))->toBeTruthy();
    });

    it('can distinguish client-side error codes', function () {
        expect(HttpStatusCode::isClientError(399))->toBeFalsy();
        expect(HttpStatusCode::isClientError(400))->toBeTruthy();
        expect(HttpStatusCode::isClientError(499))->toBeTruthy();
        expect(HttpStatusCode::isClientError(500))->toBeFalsy();
    });

    it('can distinguish server-side error codes', function () {
        expect(HttpStatusCode::isServerError(499))->toBeFalsy();
        expect(HttpStatusCode::isServerError(500))->toBeTruthy();
        expect(HttpStatusCode::isServerError(599))->toBeTruthy();
        expect(HttpStatusCode::isServerError(600))->toBeTruthy();
    });
});
