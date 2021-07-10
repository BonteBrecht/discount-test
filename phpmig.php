<?php

use Phpmig\Adapter;
use Pimple\Container;

$container = new Container();

$container['db'] = static function () {
    $dbh = new PDO('mysql:dbname=discount_test;host=127.0.0.1;port=3307','discount_test','discount_test');
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $dbh;
};

$container['phpmig.adapter'] = static fn ($c) => new Adapter\PDO\Sql($c['db'], 'migrations');

$container['phpmig.migrations_path'] = __DIR__ . DIRECTORY_SEPARATOR . 'migrations';

return $container;
