<?php
/*
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace alxmsl\Primitives\Set\Iterator;
use alxmsl\Primitives\Set\Provider\AbstractProvider;
use LogicException;
use Iterator;

/**
 * Abstract iterator class for set providers
 * @author alxmsl
 * @date 4/6/13
 */
abstract class AbstractIterator implements Iterator {
    /**
     * @var null|AbstractProvider set`s provider instance
     */
    private $Provider = null;

    /**
     * Setter for provider instance
     * @param AbstractProvider $Provider provider instance
     * @throws LogicException when enlisted mode in set is disabled
     */
    public function setProvider(AbstractProvider $Provider) {
        if (!$Provider->isEnlistedMode()) {
            throw new LogicException('Can not iterate set with disabled enlisted mode');
        }
        $this->Provider = $Provider;
    }

    /**
     * Provider instance getter
     * @return AbstractProvider provider instance
     */
    public function getProvider() {
        return $this->Provider;
    }
}
