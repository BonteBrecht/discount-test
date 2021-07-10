<?php

use App\Calculator\DiscountCalculator;
use App\Dto\Order\Order;
use App\Util\Json;
use Assert\LazyAssertionException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$container = require __DIR__ . '/../container.php';
\assert($container instanceof \Pimple\Psr11\Container);

$app = AppFactory::create();

$app->post(
    '/calculate-discount',
    static function (Request $request, Response $response, array $args) use ($container): Response {
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

        $discountCalculator = $container->get(DiscountCalculator::class);
        \assert($discountCalculator instanceof DiscountCalculator);

        $appliedDiscounts = $discountCalculator->calculateDiscounts($order);

        $response->getBody()->write(Json::encode($appliedDiscounts));

        return $response->withHeader('Content-Type', 'application/json');
    }
);

$app->run();
