<?php
/*
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace alxmsl\Primitives\Set;
use alxmsl\Primitives\Set\Provider\AbstractProvider;
use IteratorAggregate;
use LogicException;
use Traversable;

/**
 * Set instance
 * @author alxmsl
 * @date 3/31/13
 */
final class Set implements SetInterface, IteratorAggregate {
    /**
     * @var AbstractProvider storage provider for set implementation
     */
    private $Provider = null;

    /**
     * Storage provider setter
     * @param AbstractProvider $Provider storage provider
     */
    public function setProvider(AbstractProvider $Provider) {
        $this->Provider = $Provider;
    }

    /**
     * Storage provider getter
     * @return AbstractProvider storage provider
     */
    public function getProvider() {
        return $this->Provider;
    }

    /**
     * Set iterator getter
     * @return Traversable set`s iterator instance
     * @throws LogicException when provider was not define
     */
    public function getIterator() {
        if ($this->getProvider() instanceof AbstractProvider) {
            return $this->getProvider()->getIterator();
        } else {
            throw new LogicException('Provider must be defined firstly');
        }
    }

    /**
     * Add item to set
     * @param mixed $Item adding item
     * @return bool result of adding item
     * @throws LogicException when provider was not define
     */
    public function add($Item) {
        if ($this->getProvider() instanceof AbstractProvider) {
            return $this->getProvider()->add($Item);
        } else {
            throw new LogicException('Provider must be defined firstly');
        }
    }

    /**
     * Check item in set
     * @param mixed $Item checking item
     * @return bool result of check existence
     * @throws LogicException when provider was not define
     */
    public function exists($Item) {
        if ($this->getProvider() instanceof AbstractProvider) {
            return $this->getProvider()->exists($Item);
        } else {
            throw new LogicException('Provider must be defined firstly');
        }
    }
}
