<?php
/*
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace alxmsl\Primitives\Cache;
use stdClass;

/**
 * Cached value class
 * @author alxmsl
 * @date 9/3/14
 */
final class Item {
    /**
     * Value types constants
     */
    const TYPE_NULL   = 0,
          TYPE_STRING = 1,
          TYPE_ARRAY  = 2,
          TYPE_NUMBER = 3,
          TYPE_OBJECT = 4,
          TYPE_NODE   = 5;

    /**
     * @var string value field name
     */
    private $field = '';

    /**
     * @var int value type
     */
    private $type = self::TYPE_STRING;

    /**
     * @var null|mixed value
     */
    private $Value = null;

    /**
     * @var int expiration timestamp
     */
    private $expiration = 0;

    /**
     * Initialize item default value
     */
    private function initializeDefaultValue() {
        switch ($this->type) {
            case self::TYPE_ARRAY:
                $this->Value = [];
                break;
            case self::TYPE_NUMBER:
                $this->Value = 0;
                break;
            case self::TYPE_STRING:
                $this->Value = '';
                break;
            case self::TYPE_OBJECT:
                $this->Value = new stdClass();
                break;
            case self::TYPE_NODE:
                $this->Value = new stdClass();
                break;
        }
    }

    /**
     * @param string $field value field name
     * @param int $type value type
     */
    public function __construct($field, $type = self::TYPE_NULL) {
        $this->field = (string) $field;
        $this->type  = $type;
        $this->initializeDefaultValue();
    }

    /**
     * Value setter
     * @param mixed|null $Value cached value instance
     * @return Item self instance
     */
    public function setValue($Value) {
        if ($this->type == self::TYPE_NULL) {
            switch (true) {
                case is_array($Value):
                    $this->type = self::TYPE_ARRAY;
                    break;
                case is_numeric($Value):
                    $this->type = self::TYPE_NUMBER;
                    break;
                case is_object($Value):
                    if ($Value instanceof Item) {
                        $this->type = self::TYPE_NODE;
                    } else {
                        $this->type = self::TYPE_OBJECT;
                    }
                    break;
                case is_string($Value):
                default:
                    $this->type = self::TYPE_STRING;
                    break;
            }
        }
        if (is_null($this->Value)) {
            $this->initializeDefaultValue();
        }

        switch ($this->type) {
            case self::TYPE_ARRAY:
                $this->Value = (array) $Value;
                break;
            case self::TYPE_NUMBER:
                $this->Value = (int) $Value;
                break;
            case self::TYPE_OBJECT:
                $this->Value = $Value;
                break;
            case self::TYPE_NODE:
                $this->Value->{$Value->getField()} = $Value;
                break;
            case self::TYPE_STRING:
            default:
                $this->Value = (string) $Value;
                break;
        }
        return $this;
    }

    /**
     * Expiration timestamp setter
     * @param int $expiration value expiration timestamp
     * @return Item self instance
     */
    public function setExpiration($expiration) {
        $this->expiration = $expiration;
        return $this;
    }

    /**
     * Value getter
     * @return null|mixed cached value
     */
    public function getValue() {
        return $this->Value;
    }

    /**
     * Expiration timestamp getter
     * @return int expiration timestamp
     */
    public function getExpiration() {
        return $this->expiration;
    }

    /**
     * Value field name getter
     * @return string value field name
     */
    public function getField() {
        return $this->field;
    }

    /**
     * Expired value or not
     * @return bool is value expired or not
     */
    public function isExpired() {
        return $this->getExpiration() > 0
            && time() > $this->getExpiration();
    }

    /**
     * Append value
     * @param mixed $Value appended value
     */
    public function append($Value) {
        switch ($this->type) {
            case self::TYPE_NULL:
                $this->setValue($Value);
                break;
            case self::TYPE_ARRAY:
                if (is_array($Value)) {
                    $this->Value = array_merge($this->Value, $Value);
                } else {
                    array_push($this->Value, $Value);
                }
                break;
            case self::TYPE_NUMBER:
                $this->Value += (int) $Value;
                break;
            case self::TYPE_OBJECT:
                $this->Value = (object) array_merge((array) $this->Value, (array) $Value);
                break;
            case self::TYPE_NODE:
                $this->Value->{$Value->getField()} = $Value;
                break;
            case self::TYPE_STRING:
            default:
                $this->Value .= (string) $Value;
                break;
        }
    }
}
 