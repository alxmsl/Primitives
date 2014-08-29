<?php

namespace alxmsl\Primitives\Cache\Example;
use alxmsl\Primitives\Cache\Traits\CacheLevelTrait;
use alxmsl\Primitives\Cache\Traits\CacheTrait;

/**
 * Cache level 3 example class
 * @author alxmsl
 * @date 8/29/14
 */ 
class Level3Cache extends Level2Cache {
    use CacheTrait;
    use CacheLevelTrait;

    /**
     * @param string $name cache key
     */
    public function __construct($name) {
        parent::__construct($name);
        $this->name = 'level3';
    }
}
 