<?php
/*
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace alxmsl\Primitives\Set\Provider;
use alxmsl\Connection\Redis\Connection;
use alxmsl\Primitives\Set\Iterator\RedisIterator;
use RuntimeException;

/**
 * Redis implementation for set
 * @author alxmsl
 * @date 4/5/13
 */
final class RedisProvider extends AbstractProvider {
    /**
     * Duplicates sets prefix name
     */
    const PREFIX_DUPLICATE = 'DUPLICATE';

    /**
     * @var Connection redis connection instance
     */
    private $Redis = null;

    /**
     * @var null|RedisIterator set`s iterator instance
     */
    private $Iterator = null;

    /**
     * @var string set`s duplicate name cache
     */
    private $duplicateName = '';

    /**
     * Set`s duplicate name getter
     * @return string set`s duplicate name
     */
    private function getDuplicateName() {
        if (empty($this->duplicateName)) {
            $this->duplicateName = implode('_', array(
                self::PREFIX_DUPLICATE,
                $this->getName(),
            ));
        }
        return $this->duplicateName;
    }

    /**
     * Redis set`s iterator getter
     * @return RedisIterator redis set`s iterator
     */
    public function getIterator() {
        if (is_null($this->Iterator)) {
            $this->Iterator = new RedisIterator();
            $this->Iterator->setProvider($this);
        }
        return $this->Iterator;
    }

    /**
     * Redis connection setter
     * @param Connection $Redis redis connection
     * @return RedisProvider sel
     */
    public function setRedis(Connection $Redis) {
        $this->Redis = $Redis;
        return $this;
    }

    /**
     * Redis connection getter
     * @return Connection redis connection
     * @throws RuntimeException when connection is not define
     */
    private function getRedis() {
        if (is_null($this->Redis)) {
            throw new RuntimeException('redis connection was not define');
        }
        return $this->Redis;
    }

    /**
     * Add item to set
     * @param mixed $Item adding item
     * @return bool result of adding item
     */
    public function add($Item) {
        return $this->getRedis()->sadd($this->getName(), $Item);
    }

    /**
     * Check item in set
     * @param mixed $Item checking item
     * @return bool result of check existence
     */
    public function exists($Item) {
        return $this->getRedis()->sismembers($this->getName(), $Item);
    }

    /**
     * Check duplicate set existence
     * @return bool has set duplicate or not
     */
    public function hasDuplicate() {
        return $this->getRedis()->exists($this->getDuplicateName());
    }

    /**
     * Create set duplicate
     * @return int set size
     */
    public function createDuplicate() {
        return $this->getRedis()->sdiffstore($this->getDuplicateName(), array($this->getName()));
    }

    /**
     * Set item getter
     * @param mixed $offset set items offset
     * @return mixed set item
     */
    public function get($offset = null) {
        return $this->getRedis()->spop($this->getDuplicateName());
    }
}
