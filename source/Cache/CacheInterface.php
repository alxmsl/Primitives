<?php
/*
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

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
