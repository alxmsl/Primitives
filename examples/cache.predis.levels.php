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

use alxmsl\Connection\Predis\PredisFactory;
use alxmsl\Primitives\Cache\Cache;
use alxmsl\Primitives\Cache\Item;
use alxmsl\Primitives\CacheFactory;
use alxmsl\Primitives\Cache\Example\Level2Cache;
use alxmsl\Primitives\Cache\Example\Level3Cache;
use alxmsl\Primitives\Cache\Exception\MissingException;

$Connection = PredisFactory::createPredisByConfig([
    'host' => 'localhost',
    'port' => 6379,
]);

$RootCache = CacheFactory::createPredisCache('key_03', Cache::class, $Connection);
$Level2Cache = CacheFactory::createPredisCache('key_03', Level2Cache::class, $Connection);
$Level3Cache = CacheFactory::createPredisCache('key_03', Level3Cache::class, $Connection);

// Leveled value write and read
$Level3Cache->set('some_level3_key', 5, Item::TYPE_NUMBER);
unset($Level3Cache);

$Level3Cache = CacheFactory::createPredisCache('key_03', Level3Cache::class, $Connection);
var_dump($Level3Cache->get('some_level3_key')->getValue() == 5);

// Check cached level 3 value from level 2
$Level2Value = $Level2Cache->get('level3')->getValue();
var_dump($Level2Value->some_level3_key->getValue() == $Level3Cache->get('some_level3_key')->getValue());

// Check cached level 3 value from root level
$RootLevelValue = $RootCache->get('level2')->getValue();
var_dump($RootLevelValue->level3->getValue()->some_level3_key->getValue() == $Level3Cache->get('some_level3_key')->getValue());

// Set another value on level 2 and invalidate level 3
$Level2Cache->set('another_level2_key', 7);
$Level3Cache->invalidate();
unset($Level2Cache);

// Then check level 2 cached value
$Level2Cache = CacheFactory::createPredisCache('key_03', Level2Cache::class, $Connection);
var_dump($Level2Cache->get('another_level2_key')->getValue() == 7);

// Check what level 3 is empty
try {
    $Level2Cache->get('level3');
    printf("error: level3 was not delete\n");
} catch (MissingException $Ex) {
    var_dump(true);
}
