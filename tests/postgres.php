<?php
/**
 * Postgresql set test
 * @author alxmsl
 * @date 4/6/13
 */

include('../source/Autoloader.php');
include('../lib/Connection/source/Autoloader.php');

use Set\SetFactory,
    Connection\Postgresql\Client\Connection;

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