<?php
/**
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 *
 * Cache arrays usage example
 * @author alxmsl
 * @date 8/28/14
 */

include '../vendor/autoload.php';

use alxmsl\Primitives\Cache\Cache;
use alxmsl\Primitives\Cache\Item;
use alxmsl\Primitives\CacheFactory;

$Connection = new Memcached('cache');
$Connection->addServer('localhost', 11211);

$Cache = CacheFactory::createMemcachedCache('key_02', Cache::class, $Connection);
$Cache->invalidate();
unset($Cache);

// Append array example
$Cache = CacheFactory::createMemcachedCache('key_02', Cache::class, $Connection);
$Cache->invalidate();
$Cache->append('some_array', 7, Item::TYPE_ARRAY);
unset($Cache);

$Cache = CacheFactory::createMemcachedCache('key_02', Cache::class, $Connection);
$Cache->append('some_array', 1);
var_dump($Cache->get('some_array')->getValue() == [7, 1]);

// Increment example
$Cache = CacheFactory::createMemcachedCache('key_02', Cache::class, $Connection);
$Cache->append('some_number', 7, Item::TYPE_NUMBER);
unset($Cache);

$Cache = CacheFactory::createMemcachedCache('key_02', Cache::class, $Connection);
$Cache->append('some_number', 2);
var_dump($Cache->get('some_number')->getValue() == 9);

// Strings concatenation example
$Cache = CacheFactory::createMemcachedCache('key_02', Cache::class, $Connection);
$Cache->append('some_string', 7, Item::TYPE_STRING);
unset($Cache);

$Cache = CacheFactory::createMemcachedCache('key_02', Cache::class, $Connection);
$Cache->append('some_string', 2);
var_dump($Cache->get('some_string')->getValue() == '72');
