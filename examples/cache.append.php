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
use alxmsl\Primitives\CacheFactory;

$Connection = new Memcached('cache');
$Connection->addServer('localhost', 11211);

$Cache = CacheFactory::createMemcachedCache('key_02', Cache::getClass(), $Connection);
$Cache->invalidate();
$Cache->append('array', 7);
unset($Cache);

$Cache = CacheFactory::createMemcachedCache('key_02', Cache::getClass(), $Connection);
$Cache->append('array', 1);
var_dump($Cache->get('array') == [7, 1]);