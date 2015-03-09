<?php
/**
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 *
 * Cache on predis usage example
 * @author alxmsl
 */

include '../vendor/autoload.php';

use alxmsl\Connection\Predis\PredisFactory;
use alxmsl\Primitives\Cache\Cache;
use alxmsl\Primitives\Cache\Item;
use alxmsl\Primitives\Cache\Exception\ExpiredException;
use alxmsl\Primitives\Cache\Exception\MissingException;
use alxmsl\Primitives\CacheFactory;

$Client = PredisFactory::createPredisByConfig([
    'host' => 'localhost',
    'port' => 6379,
]);

$Cache = CacheFactory::createPredisCache('key_01', Cache::class, $Client);

// Cache missing example
$key = 'value_' . mt_rand(100, 500);
try {
    $Cache->get($key);
    printf("error: key %s found\n", $key);
} catch (MissingException $Ex) {}

// Cached value expiration example
$Cache->set($key, 7, Item::TYPE_NUMBER, time() + 1);
sleep(2);
try {
    $Cache->get($key);
    printf("error: key %s not expired\n", $key);
} catch (ExpiredException $Ex) {}

$Cache->set('some_key', 7);
unset($Cache);

$Cache = CacheFactory::createPredisCache('key_01', Cache::class, $Client);
var_dump($Cache->get('some_key')->getValue() == 7);
