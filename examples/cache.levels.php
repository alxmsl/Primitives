<?php
/**
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 *
 * Hierarchical cache usage example
 * @author alxmsl
 * @date 8/28/14
 */

include '../vendor/autoload.php';

use alxmsl\Primitives\Cache\Cache;
use alxmsl\Primitives\CacheFactory;
use alxmsl\Primitives\Cache\Example\Level2Cache;
use alxmsl\Primitives\Cache\Example\Level3Cache;
use alxmsl\Primitives\Cache\Exception\MissingException;

$Connection = new Memcached('cache');
$Connection->addServer('localhost', 11211);
$RootCache = CacheFactory::createMemcachedCache('key_03', Cache::getClass(), $Connection);
$Level2Cache = CacheFactory::createMemcachedCache('key_03', Level2Cache::getClass(), $Connection);
$Level3Cache = CacheFactory::createMemcachedCache('key_03', Level3Cache::getClass(), $Connection);

// Cache value on level 3
$Level3Cache->set('level3_key', 5);

// Check value from level 2
$Level2Value = $Level2Cache->get('level3');
var_dump($Level2Value->level3_key == $Level3Cache->get('level3_key'));

// Check value from root level
$RootLevelValue = $RootCache->get('level2');
var_dump($RootLevelValue->level3->level3_key == $Level3Cache->get('level3_key'));

// Set another value on level 2 and invalidate level 3
$Level2Cache->set('level2_key', 7);
$Level3Cache->invalidate();
unset($Level2Cache);

// Then check level 2 cached value
$Level2Cache = CacheFactory::createMemcachedCache('key_03', Level2Cache::getClass(), $Connection);
var_dump($Level2Cache->get('level2_key') == 7);

// Check what level 3 is empty
try {
    $Level2Cache->get('level3');
    printf("error on level3 deletion\n");
} catch (MissingException $Ex) {
    printf("level3 removed correctly\n");
}