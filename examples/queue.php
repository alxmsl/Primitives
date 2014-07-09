<?php
/**
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 *
 * Queues usage example
 * @author alxmsl
 * @date 7/10/14
 */

include '../vendor/autoload.php';

use alxmsl\Connection\Redis\RedisFactory;
use alxmsl\Primitives\QueueFactory;

$Connection = RedisFactory::createRedisByConfig(array(
    'host' => 'localhost',
    'port' => 6379,
));

$Queue = QueueFactory::createRedisQueue('myqueue_01', $Connection);

$queue = array(1, 2, 4, 5, 6, 7, 8, 5);
foreach ($queue as $item) {
    $Queue->enqueue($item);
}

$result = array();
for (;;) {
    $item = $Queue->dequeue();
    if ($item !== false) {
        $result[] = $item;
    } else {
        break;
    }
}

$diff = array_diff($queue, $result);
var_dump(empty($diff));
