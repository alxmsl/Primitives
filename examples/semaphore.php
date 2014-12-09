<?php
/**
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 *
 * Redis semaphore test
 * @author alxmsl
 * @date 4/5/13
 */

include('../source/Autoloader.php');
include('../vendor/alxmsl/connection/source/Autoloader.php');

use alxmsl\Connection\Redis\RedisFactory;
use alxmsl\Primitives\SemaphoreFactory;

// Create redis connection
$Connection = RedisFactory::createRedisByConfig(array(
    'host' => 'localhost',
    'port' => 6379,
));

// Create semaphore instance
$Semaphore = SemaphoreFactory::createRedisSemaphore($Connection, 'locker');

// Use semaphore
$Semaphore->wait();
sleep(1);
$Semaphore->signal();
