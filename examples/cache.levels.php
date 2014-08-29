<?php
/**
 * Hierarchical cache usage example
 * @author alxmsl
 * @date 8/28/14
 */

include '../vendor/autoload.php';

use alxmsl\Primitives\Cache\Cache;
use alxmsl\Primitives\Cache\Example\Level2Cache;
use alxmsl\Primitives\Cache\Example\Level3Cache;
use alxmsl\Primitives\Cache\Exception\MissingException;
use alxmsl\Primitives\Cache\Provider\MemcachedProvider;

$Client = new Memcached('cache');
$Client->addServer('localhost', 11211);

$Provider = new MemcachedProvider();
$Provider->setConnection($Client);

$RootCache = new Cache('key_03');
$RootCache->setProvider($Provider);

$Level2Cache = new Level2Cache('key_03');
$Level2Cache->setProvider($Provider);

$Level3Cache = new Level3Cache('key_03');
$Level3Cache->setProvider($Provider);

$Level3Cache->set('level3_key', 5);
$Level2Value = $Level2Cache->get('level3');
var_dump($Level2Value->level3_key == $Level3Cache->get('level3_key'));
$RootLevelValue = $RootCache->get('level2');
var_dump($RootLevelValue->level3->level3_key == $Level3Cache->get('level3_key'));

$Level2Cache->set('level2_key', 7);
$Level3Cache->invalidate();
unset($Level2Cache);

$Level2Cache = new Level2Cache('key_03');
$Level2Cache->setProvider($Provider);
var_dump($Level2Cache->get('level2_key') == 7);
try {
    $Level2Cache->get('level3');
    printf("error on level3 deletion\n");
} catch (MissingException $Ex) {
    printf("level3 removed correctly\n");
}