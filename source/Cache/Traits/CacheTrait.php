<?php
/*
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace alxmsl\Primitives\Cache\Traits;
use alxmsl\Primitives\Cache\Exception\MissingException;
use stdClass;

/**
 * Leveled cache instance mixin
 * @author alxmsl
 * @date 8/29/14
 */
trait CacheTrait {
    /**
     * @var string cache name
     */
    private $name = '';

    /**
     * @var null|stdClass cached value
     */
    private static $Value = null;

    /**
     * Cached field value getter
     * @param string $field cached field
     * @return mixed cached value
     */
    protected function getValueField($field) {
        if (!isset(self::$Value->{$field})) {
            self::$Value->{$field} = new stdClass();
        }
        return self::$Value->{$field};
    }

    /**
     * Unset cached value for field
     * @param string $field needed field name
     */
    protected function unsetValueField($field) {
        unset(self::$Value->{$field});
    }

    /**
     * Cached value getter
     * @param string $field field name
     * @return mixed cached value
     * @throws MissingException when needed field not found
     */
    public function get($field) {
        $this->load(false);
        if (isset(self::$Value->{$field})) {
            return self::$Value->{$field};
        } else {
            throw new MissingException(sprintf('field %s not found', $field));
        }
    }

    /**
     * Caching setter
     * @param string $field caching field name
     * @param mixed $Value caching value
     */
    public function set($field, $Value) {
        if (is_null(self::$Value)) {
            $this->load(false);
        }

        if (is_null(self::$Value)) {
            self::$Value = new stdClass();
        }

        self::$Value->{$field} = $Value;
        $this->save();
    }

    /**
     * Caching appender
     * @param string $field caching field name
     * @param mixed $Value appending value
     */
    public function append($field, $Value) {
        $this->load(true);

        if (is_null(self::$Value)) {
            self::$Value = new stdClass();
        }

        if (!isset(self::$Value->{$field})) {
            self::$Value->{$field} = [];
        }
        array_push(self::$Value->{$field}, $Value);
        $this->save(true);
    }

    /**
     * Invalidate cache
     */
    public function invalidate() {
        $this->clear();
        self::$Value = null;
        $this->save();
    }
}
