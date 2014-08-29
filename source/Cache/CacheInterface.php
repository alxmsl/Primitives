<?php

namespace alxmsl\Primitives\Cache;
use alxmsl\Primitives\Cache\Exception\MissingException;

/**
 * Interface for caching system
 * @author alxmsl
 * @date 8/28/14
 */
interface CacheInterface {
    /**
     * Cached value getter
     * @param string $field field name
     * @return mixed cached value
     * @throws MissingException when needed field not found
     */
    public function get($field);

    /**
     * Caching setter
     * @param string $field caching field name
     * @param mixed $Value caching value
     */
    public function set($field, $Value);

    /**
     * Caching appender
     * @param string $field caching field name
     * @param mixed $Value appending value
     */
    public function append($field, $Value);

    /**
     * Invalidate cache
     */
    public function invalidate();
}
