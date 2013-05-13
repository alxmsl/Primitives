<?php

namespace Set\Iterator;

use Set\Provider\AbstractProvider;

/**
 *
 * @author alxmsl
 * @date 4/6/13
 */
abstract class AbstractIterator implements \Iterator {

    private $Provider = null;

    public function setProvider(AbstractProvider $Provider) {
        if (!$Provider->isEnlistedMode()) {
            throw new \LogicException('Can not iterate set with disabled enlisted mode');
        }
        $this->Provider = $Provider;
    }

    /**
     * @return AbstractProvider
     */
    public function getProvider() {
        return $this->Provider;
    }
}
