<?php
/**
 * Simple cache usage example
 * @author alxmsl
 * @date 8/28/14
 */

include '../vendor/autoload.php';

use alxmsl\Primitives\Cache\Cache;
use alxmsl\Primitives\Cache\Provider\MemcachedProvider;

$Client = new Memcached('cache');
$Client->addServer('localhost', 11211);

$Provider = new MemcachedProvider();
$Provider->setConnection($Client);

$Cache = new Cache('key_01');
$Cache->setProvider($Provider);
$Cache->set('value', 7);
unset($Cache);

$Cache = new Cache('key_01');
$Cache->setProvider($Provider);
var_dump($Cache->get('value') == 7);