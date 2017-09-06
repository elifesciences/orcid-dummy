<?php

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\HttpKernelInterface;

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

$app->get('/oauth2/authorize', function(Request $request) {
    $redirectUri = $request->query->get('redirect_uri');
    $state = $request->query->get('state');
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

$app->error(function (Throwable $e) {
    if ($e instanceof HttpExceptionInterface) {
        $status = $e->getStatusCode();
    } else {
        $status = 500;
    }
    return new Response(
        json_encode(['message' => $e->getMessage()], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES),
        $status
    );
});

return $app;
