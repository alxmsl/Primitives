<?php
/**
 * Cache arrays usage example
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

$Cache = new Cache('key_02');
$Cache->setProvider($Provider);
$Cache->invalidate();

$Cache->append('array', 7);
unset($Cache);

$Cache = new Cache('key_02');
$Cache->setProvider($Provider);
$Cache->append('array', 1);

var_dump($Cache->get('array') == [7, 1]);