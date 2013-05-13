<?php

namespace Set;

use Set\Provider\AbstractProvider;

/**
 * Set instance
 * @author alxmsl
 * @date 3/31/13
 */
final class Set implements SetInterface, \IteratorAggregate {
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
     * @return \Traversable
     * @throws \LogicException when provider was not define
     */
    public function getIterator() {
        if ($this->getProvider() instanceof AbstractProvider) {
            return $this->getProvider()->getIterator();
        } else {
            throw new \LogicException('Provider must be defined firstly');
        }
    }

    /**
     * Add item to set
     * @param mixed $Item adding item
     * @return bool result of adding item
     * @throws \LogicException when provider was not define
     */
    public function add($Item) {
        if ($this->getProvider() instanceof AbstractProvider) {
            return $this->getProvider()->add($Item);
        } else {
            throw new \LogicException('Provider must be defined firstly');
        }
    }

    /**
     * Check item in set
     * @param mixed $Item checking item
     * @return bool result of check existance
     * @throws \LogicException when provider was not define
     */
    public function exists($Item) {
        if ($this->getProvider() instanceof AbstractProvider) {
            return $this->getProvider()->exists($Item);
        } else {
            throw new \LogicException('Provider must be defined firstly');
        }
    }
}
