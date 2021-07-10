<?php

use Pimple\Container;
use Pimple\Psr11\Container as Psr11Container;

$container = new Container();

$container['db'] = static function () {
    $dbh = new PDO('mysql:dbname=discount_test;host=127.0.0.1;port=3307','discount_test','discount_test');
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $dbh;
};

return new Psr11Container($container);
