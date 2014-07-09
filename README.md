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

License
-------
Copyright © 2014 Alexey Maslov <alexey.y.maslov@gmail.com>
This work is free. You can redistribute it and/or modify it under the
terms of the Do What The Fuck You Want To Public License, Version 2,
as published by Sam Hocevar. See the COPYING file for more details.
