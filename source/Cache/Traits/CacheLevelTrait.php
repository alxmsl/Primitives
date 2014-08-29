<?php

namespace alxmsl\Primitives\Cache\Traits;

/**
 * Secondary levels cache instance mixin
 * @author alxmsl
 * @date 8/29/14
 */
trait CacheLevelTrait {
    /**
     * Load cache data
     * @param bool $useCas use transaction for loading or not
     */
    protected function load($useCas = false) {
        parent::load($useCas);
        self::$Value = parent::getValueField($this->name);
    }

    /**
     * Save cache data
     * @param bool $useCas use transaction or not
     */
    protected function save($useCas = false) {
        parent::save($useCas);
    }

    /**
     * Clear cache data
     */
    protected function clear() {
        parent::unsetValueField($this->name);
    }
}
