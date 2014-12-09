Primitives
===

Base classes for abstract primitives: sets, queues etc.

Set on Redis storage usage example
-------

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

    // Check items existence
    var_dump($Set->exists('obj_01'), $Set->exists('obj_03'));

Set on Postgres table usage example
-------

    use alxmsl\Connection\Postgresql\Connection;
    use alxmsl\Primitives\SetFactory;

    // Create new postgres connection
    $Connection = new Connection();
    $Connection->setNeedBusyCheckup(false)
        ->setUserName('postgres')
        ->setPassword('postgres')
        ->setDatabase('postgres')
        ->setHost('localhost')
        ->setPort(5432);

    // Create set instance
    $Set = SetFactory::createPostgresSet('test', $Connection);

    // Add items to set
    $Set->add('obj_01');
    $Set->add('obj_02');

    // Check items existence
    var_dump($Set->exists('obj_01'), $Set->exists('obj_03'));

Set`s iteration
-------

With set instance you can use an iterator support. Just enable enlisted mode

    $Set->getProvider()->setEnlistedMode(true);

And use set`s iterator

    foreach ($Set->getIterator() as $item) {
        var_dump($item);
    }

Queue on Redis storage usage example
-------

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

Of course, queue instance implements[Iterator](php.net/manual/class.iterator.php) interface. Usage example:

    foreach ($queue as $item) {
        $Queue->enqueue($item);
    }

    $result = array();
    foreach ($Queue->getIterator() as $item) {
        $result[] = $item;
    }

    $diff = array_diff($queue, $result);
    var_dump(empty($diff));

Queues pool usage example
-------

    $Queue1 = QueueFactory::createRedisQueue('myqueue_pool_01', $Connection1);
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

Hierarchical cache usage example
-------

    $RootCache = CacheFactory::createMemcachedCache('key_03', Cache::getClass(), $Connection);
    $Level2Cache = CacheFactory::createMemcachedCache('key_03', Level2Cache::getClass(), $Connection);
    $Level3Cache = CacheFactory::createMemcachedCache('key_03', Level3Cache::getClass(), $Connection);

    // Leveled value write and read
    $Level3Cache->set('some_level3_key', 5, Item::TYPE_NUMBER);
    unset($Level3Cache);

    $Level3Cache = CacheFactory::createMemcachedCache('key_03', Level3Cache::getClass(), $Connection);
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
    $Level2Cache = CacheFactory::createMemcachedCache('key_03', Level2Cache::getClass(), $Connection);
    var_dump($Level2Cache->get('another_level2_key')->getValue() == 7);

    // Check what level 3 is empty
    try {
        $Level2Cache->get('level3');
        printf("error: level3 was not delete\n");
    } catch (MissingException $Ex) {
        var_dump(true);
    }

Semaphore usage example
-------



License
-------
Copyright © 2014 Alexey Maslov <alexey.y.maslov@gmail.com>
This work is free. You can redistribute it and/or modify it under the
terms of the Do What The Fuck You Want To Public License, Version 2,
as published by Sam Hocevar. See the COPYING file for more details.
