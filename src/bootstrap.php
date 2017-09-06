<?php

use Silex\Application;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

require_once __DIR__.'/../vendor/autoload.php';

$app = new Application();

$app->get('ping', function () {
    return new Response(
        'pong',
        Response::HTTP_OK,
        [
            'Cache-Control' => 'must-revalidate, no-cache, no-store, private',
            'Content-Type' => 'text/plain; charset=UTF-8',
        ]
    );
});

$app->get('/oauth2/authorize', function (Request $request) {
    $redirectUri = $request->get('redirect_uri');
    $state = $request->get('state');
    $code = 'code_'.$state;
    $location = $redirectUri.'?'.http_build_query([
        'code' => $code,
        'state' => $state,
    ]);

    return new Response(
        'pong',
        Response::HTTP_FOUND,
        [
            'Location' => $location,
        ]
    );
});

$app->post('/oauth2/token', function (Request $request) {
    $code = $request->get('code');

    return new JsonResponse([
        'access_token' => 'access_token_'.$code,
        'token_type' => 'bearer',
        'refresh_token' => 'refresh_token_'.$code,
        'expires_in' => 30 * 24 * 60 * 60,
        'scope' => '/authenticate',
        'orcid' => '0000-0002-1825-0097',
        'name' => 'Josiah Carberry',
    ]);
});

$app->error(function (Throwable $e) {
    if ($e instanceof HttpExceptionInterface) {
        $status = $e->getStatusCode();
    } else {
        $status = 500;
    }
    return new JsonResponse(
        [
            'message' => $e->getMessage(),
        ],
        $status
    );
});

return $app;
