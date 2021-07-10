<?php

use App\Dto\Order\Order;
use Assert\LazyAssertionException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$container = require __DIR__ . '/../container.php';
\assert($container instanceof \Pimple\Psr11\Container);

$app = AppFactory::create();

$app->get(
    '/',
    static function (Request $request, Response $response, array $args): Response {
        $response->getBody()->write("Hello world!");
        return $response;
    }
);

$app->get(
    '/calculate-discount',
    static function (Request $request, Response $response, array $args): Response {
        try {
            $data = \json_decode($request->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException) {
            $data = null;
        }
        if (!\is_array($data)) {
            return $response->withStatus(400);
        }
        try {
            $order = Order::createFromArray($data);
        } catch (LazyAssertionException $e) {
            $response->getBody()->write($e->getMessage());

            return $response->withStatus(422);
        }

        $response->getBody()->write("Hello world!");
        return $response;
    }
);

$app->run();
