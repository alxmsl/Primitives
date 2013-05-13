<?php
/**
 * Redis set test
 * @author alxmsl
 * @date 4/5/13
 */

include('../source/Autoloader.php');
include('../lib/Connection/source/Autoloader.php');

use Set\SetFactory,
    Connection\Redis\RedisFactory;

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