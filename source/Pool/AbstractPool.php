<?php
/*
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace alxmsl\Primitives\Pool;

/**
 * Abstract pool of instances
 * @author alxmsl
 * @date 8/5/14
 */ 
abstract class AbstractPool {
    /**
     * @var PoolInstance|null instance generator
     */
    private $Instance = null;

    /**
     * Instance generator getter
     * @return PoolInstance|null instance generator
     */
    protected function getInstance() {
        return $this->Instance;
    }

    public function __construct() {
        $this->Instance = new PoolInstance();
    }
}
