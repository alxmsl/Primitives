<?php
/*
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

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
     * @param bool $forceReload force reload stored data
     */
    protected function load($useCas = false, $forceReload = false) {
        parent::load($useCas, $forceReload);
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
