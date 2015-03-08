<?php
/*
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace alxmsl\Primitives\Cache\Provider;
use alxmsl\Primitives\Cache\Exception\CasErrorException;
use alxmsl\Primitives\Cache\Item;
use stdClass;

/**
 * Cache storage provider interface
 * @author alxmsl
 * @date 8/29/14
 */
interface ProviderInterface {
    /**
     * Get key value from cache storage
     * @param string $key key from storage
     * @param bool $useCas use transaction or not
     * @return Item stored item instance
     */
    public function get($key, $useCas = false);

    /**
     * Set value by key in storage
     * @param string $key key from storage
     * @param Item $Value store value
     * @param bool $useCas use transaction or not
     * @throws CasErrorException when CAS operation failed
     */
    public function set($key, $Value, $useCas = false);

    /**
     * Remove key value from storage
     * @param string $key key from storage
     */
    public function remove($key);
}
