<?php
/**
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 *
 * Redis set test
 * @author alxmsl
 * @date 4/5/13
 */

include('../source/Autoloader.php');
include('../vendor/alxmsl/connection/source/Autoloader.php');

use alxmsl\Connection\Redis\RedisFactory;
use alxmsl\Primitives\SetFactory;

// Create redis connection
$Connection = RedisFactory::createRedisByConfig(array(
    'host' => 'localhost',
    'port' => 6379,
));

// Create set on the redis connection
$Set = SetFactory::createRedisSet('test', $Connection);

// Add set elements
$Set->add('obj_01');
$Set->add('obj_02');

// Check items existance
var_dump($Set->exists('obj_01'), $Set->exists('obj_03'));

$Set->getProvider()->setEnlistedMode(true);
foreach ($Set->getIterator() as $item) {
    var_dump($item);
}
