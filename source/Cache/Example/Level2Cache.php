<?php
/*
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

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
 