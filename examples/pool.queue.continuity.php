<?php
/**
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 *
 * Real-time work (read-writing) with pool
 * @author alxmsl
 * @date 8/21/14
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

// Create second queue
$Connection3 = RedisFactory::createRedisByConfig(array(
    'host' => 'localhost',
    'port' => 6379,
));
$Connection3->select(2);
$Queue3 = QueueFactory::createRedisQueue('myqueue_pool_03', $Connection3);

// Create new pool
$Pool = new Pool();
$Pool->addQueue($Queue1)
    ->addQueue($Queue2)
    ->addQueue($Queue3);


for(;;) {
    $chance = mt_rand(0, 100);
    if ($chance < 25) {
        $Pool->enqueue($chance);
        printf("%s ->\n", $chance);
    } else {
        $item = $Pool->dequeue();
        if ($item === false) {
            printf("...\n");
        } else {
            printf("-> %s\n", $item);
        }
    }
    sleep(1);
}
