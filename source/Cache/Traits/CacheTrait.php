<?php
/*
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace alxmsl\Primitives\Cache\Traits;
use alxmsl\Primitives\Cache\Cache;
use alxmsl\Primitives\Cache\CacheInterface;
use alxmsl\Primitives\Cache\Exception\CasErrorException;
use alxmsl\Primitives\Cache\Exception\ExpiredException;
use alxmsl\Primitives\Cache\Exception\MissingException;
use alxmsl\Primitives\Cache\Item;
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
     * @var null|Item cached value
     */
    private static $Value = null;

    /**
     * Cached field value getter
     * @param string $field cached field
     * @return mixed cached value
     */
    protected function getValueField($field) {
        if (is_null(self::$Value->getValue())
            || !isset(self::$Value->getValue()->{$field})) {

            $Item = new Item($field);
            self::$Value->setValue($Item);
        }
        return self::$Value->getValue()->{$field};
    }

    /**
     * Unset cached value for field
     * @param string $field needed field name
     */
    protected function unsetValueField($field) {
        unset(self::$Value->getValue()->{$field});
    }

    /**
     * Cached value getter
     * @param string $field field name
     * @return Item cached value
     * @throws MissingException when needed field not found
     * @throws ExpiredException when needed field expired
     */
    public function get($field) {
        $this->load(false);
        if (!is_null(self::$Value->getValue())) {
            /** @var stdClass $Value */
            $Value = self::$Value->getValue();
            if (isset($Value->{$field})) {
                /** @var Item $Item */
                $Item = $Value->{$field};
                if ($Item->isExpired()) {
                    $this->unsetValueField($field);
                    throw new ExpiredException(sprintf('values from field %s was expire', $field));
                } else {
                    return $Item;
                }
            }
        }
        throw new MissingException(sprintf('field %s not found', $field));
    }

    /**
     * Caching setter
     * @param string $field caching field name
     * @param mixed $Value caching value
     * @param int $type value type
     * @param int $expiration expiration timestamp
     */
    public function set($field, $Value, $type = Item::TYPE_STRING, $expiration = 0) {
        $this->load(true, true);
        $Item = new Item($field, $type);
        $Item->setValue($Value)
            ->setExpiration($expiration);
        self::$Value->setValue($Item);
        $this->save(true);
    }

    /**
     * Caching appender
     * @param string $field caching field name
     * @param mixed $Value appending value
     * @param int $type value type
     * @param int $expiration expiration timestamp
     * @param int $tries append tries count
     * @throws CasErrorException CAS operation exception
     */
    public function append($field, $Value, $type = Item::TYPE_STRING, $expiration = 0, $tries = 3) {
        if ($tries > 0) {
            $this->load(true, true);

            if (is_null(self::$Value->getValue())
                || !isset(self::$Value->getValue()->{$field})) {

                $Item = new Item($field, $type);
            } else {
                /** @var Item $Item */
                $Item = self::$Value->getValue()->{$field};
            }

            $Item->setExpiration($expiration)
                ->append($Value);
            self::$Value->setValue($Item);
            try {
                $this->save(true);
            } catch (CasErrorException $Ex) {
                $this->append($field, $Value, $type, $expiration, $tries - 1);
            }
        } else {
            throw new CasErrorException('could not append value to cache');
        }
    }

    /**
     * Invalidate cache
     */
    public function invalidate() {
        $this->load(true, true);
        $this->clear();
        self::$Value = null;
        $this->save(true);
    }
}
