<?php

namespace alxmsl\Primitives\Queue\Iterator;
use alxmsl\Primitives\Queue\Provider\AbstractProvider;
use Iterator;

/**
 * Abstract queue iterator class
 * @author alxmsl
 * @date 7/10/14
 */ 
abstract class AbstractIterator implements Iterator {
    /**
     * @var null|AbstractProvider queue storage provider instance
     */
    private $Provider = null;

    /**
     * Setter for queue storage provider
     * @param AbstractProvider $Provider queue storage provider instance
     */
    public function setProvider(AbstractProvider $Provider) {
        $this->Provider = $Provider;
    }

    /**
     * Queue storage provider instance getter
     * @return AbstractProvider queue storage provider instance
     */
    public function getProvider() {
        return $this->Provider;
    }
}
