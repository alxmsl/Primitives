<?php

namespace alxmsl\Primitives\Cache\Provider;
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
     * @return stdClass value from storage
     */
    public function get($key, $useCas);

    /**
     * Set value by key in storage
     * @param string $key key from storage
     * @param mixed $value store value
     * @param bool $useCas use transaction or not
     */
    public function set($key, $value, $useCas);

    /**
     * Remove key value from storage
     * @param string $key key from storage
     */
    public function remove($key);
}
