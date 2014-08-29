<?php

namespace alxmsl\Primitives\Cache\Example;
use alxmsl\Primitives\Cache\Cache;
use alxmsl\Primitives\Cache\Traits\CacheLevelTrait;
use alxmsl\Primitives\Cache\Traits\CacheTrait;

/**
 * Cache level 2 example calss
 * @author alxmsl
 * @date 8/29/14
 */ 
class Level2Cache extends Cache {
    use CacheTrait;
    use CacheLevelTrait;

    /**
     * @param string $name cache key
     */
    public function __construct($name) {
        parent::__construct($name);
        $this->name = 'level2';
    }
}
 