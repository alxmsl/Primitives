<?php
/**
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 *
 * Queue pool example
 * @author alxmsl
 * @date 8/5/14
 */

include '../vendor/autoload.php';

use alxmsl\Connection\Redis\RedisFactory;
use alxmsl\Primitives\QueueFactory;
use alxmsl\Primitives\Queue\Pool\Pool;

// Create first queue
$Connection1 = RedisFactory::createRedisByConfig(array(
    'host' => 'localhost',
    'port' => 6379,
));
$Connection1->select(1);
$Queue1 = QueueFactory::createRedisQueue('myqueue_pool_01', $Connection1);

// Create second queue
$Connection2 = RedisFactory::createRedisByConfig(array(
    'host' => 'localhost',
    'port' => 6379,
));
$Connection2->select(2);
$Queue2 = QueueFactory::createRedisQueue('myqueue_pool_02', $Connection2);

// Create new pool
$Pool = new Pool();
$Pool->addQueue($Queue1)
    ->addQueue($Queue2);

// Write to pool
$items = range(1, 5);
foreach ($items as $item) {
    $Pool->enqueue($item);
    printf("enqueued: %s\n", $item);
}

// Flush pool
while ($Item = $Pool->dequeue()) {
    printf("dequeued: %s\n", $Item);
}
