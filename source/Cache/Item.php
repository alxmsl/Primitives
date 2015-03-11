<?php
/*
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace alxmsl\Primitives\Cache;
use JsonSerializable;
use Serializable;
use stdClass;

/**
 * Cached value class
 * @author alxmsl
 * @date 9/3/14
 */
final class Item implements JsonSerializable, Serializable {
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
     * @var null|stdClass|Item|mixed value
     */
    private $Value = null;

    /**
     * @var int expiration timestamp
     */
    private $expiration = 0;

    /**
     * @param int $expiration expiration timestamp
     * @return Item self instance
     */
    public function setExpiration($expiration) {
        $this->expiration = (int) $expiration;
        return $this;
    }

    /**
     * @return int expiration timestamp
     */
    public function getExpiration() {
        return $this->expiration;
    }

    /**
     * Expired value or not
     * @return bool is value expired or not
     */
    public function isExpired() {
        return ($this->getExpiration() > 0)
            && (time() > $this->getExpiration());
    }

    /**
     * @param null|stdClass|Item|mixed $Value cached value instance
     * @return Item self instance
     */
    public function setValue($Value) {
        $this->makeTypeCorrectionForValue($Value);
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
     * @return null|stdClass|mixed cached value
     */
    public function getValue() {
        return $this->Value;
    }

    /**
     * @return string value field name
     */
    public function getField() {
        return $this->field;
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
     * Append value method
     * @param null|stdClass|Item|mixed $Value appended value
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

    /**
     * Initialize default value for item
     */
    private function initializeDefaultValue() {
        switch ($this->type) {
            case self::TYPE_NULL:
                $this->Value = null;
                break;
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
     * Makes type correction for value
     * @param null|stdClass|Item|mixed $Value cached value
     */
    private function makeTypeCorrectionForValue($Value) {
        switch (true) {
            case is_null($Value):
                $this->type = self::TYPE_NULL;
                break;
            case is_array($Value):
                $this->type = self::TYPE_ARRAY;
                break;
            case is_numeric($Value):
                $this->type = self::TYPE_NUMBER;
                break;
            case is_object($Value):
                $this->type = ($Value instanceof Item)
                    ? self::TYPE_NODE
                    : self::TYPE_OBJECT;
                break;
            case is_string($Value):
            default:
                $this->type = self::TYPE_STRING;
                break;
        }
    }

    /**
     * Import item data method
     * @param array $data item data
     * @return Item self item
     */
    private function import($data) {
        $this->field      = (string) $data[0];
        $this->type       = (int) $data[1];
        $this->expiration = (int) $data[2];
        if ($this->type == 5) {
            $this->Value = new stdClass();
            foreach ($data[3] as $nodeKey => $nodeValue) {
                $Item = new Item($nodeValue[0]);
                $this->Value->{$nodeKey} = $Item->import($nodeValue);
            }
        } else {
            $this->Value = $data[3];
        }
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function jsonSerialize() {
        return [
            0 => $this->getField(),
            1 => $this->type,
            2 => $this->getExpiration(),
            3 => $this->Value,
        ];
    }

    /**
     * @inheritdoc
     */
    public function serialize() {
        return json_encode($this);
    }

    /**
     * @inheritdoc
     */
    public function unserialize($serialized) {
        $data = json_decode($serialized, true);
        $this->import($data);
    }

    /**
     * @inheritdoc
     */
    public function __toString() {
        return $this->serialize();
    }
}
 