<?php

use App\Calculator\DiscountCalculator;
use App\Repository\Customer\CustomerRepository;
use App\Repository\Customer\PdoCustomerRepository;
use App\Repository\Product\PdoProductRepository;
use App\Repository\Product\ProductRepository;
use Pimple\Container;
use Pimple\Psr11\Container as Psr11Container;

$container = new Container();

$container['db'] = static function (): PDO {
    $dbh = new PDO('mysql:dbname=discount_test;host=127.0.0.1;port=3307','discount_test','discount_test');
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $dbh;
};

$container[CustomerRepository::class] = static fn (Container $c): PdoCustomerRepository => new PdoCustomerRepository($c['db']);
$container[ProductRepository::class] = static fn (Container $c): PdoProductRepository => new PdoProductRepository($c['db']);

$container[DiscountCalculator::class] = static fn (Container $c): DiscountCalculator  => new DiscountCalculator(
    $c[CustomerRepository::class],
    $c[ProductRepository::class],
);

return new Psr11Container($container);
