<?php
/*
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace alxmsl\Primitives\Queue\Provider;
use alxmsl\Primitives\Queue\QueueInterface;
use IteratorAggregate;

/**
 * Abstract queue storage provider class
 * @author alxmsl
 * @date 7/9/14
 */ 
abstract class AbstractProvider implements QueueInterface, IteratorAggregate {
    /**
     * @var string queue name
     */
    private $name = '';

    /**
     * Queue name setter
     * @param string $name queue name
     * @return AbstractProvider self instance
     */
    public function setName($name) {
        $this->name = (string) $name;
        return $this;
    }

    /**
     * Queue name getter
     * @return string queue name
     */
    public function getName() {
        return $this->name;
    }
}
 