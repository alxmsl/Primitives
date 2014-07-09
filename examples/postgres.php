<?php
/**
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 *
 * Postgres set test
 * @author alxmsl
 * @date 4/6/13
 */

include('../source/Autoloader.php');
include('../vendor/alxmsl/connection/source/Autoloader.php');

use alxmsl\Connection\Postgresql\Connection;
use alxmsl\Primitives\SetFactory;

// Create new postgres connection
$Connection = new Connection();
$Connection->setUserName('postgres')
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

$v = $Set->getProvider()->get(5);
var_dump($v);

foreach ($Set->getIterator() as $item) {
    var_dump($item);
}
