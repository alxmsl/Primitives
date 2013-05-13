<?php

namespace Set;

/**
 * Interface for set
 * @author alxmsl
 * @date 3/31/13
 */
interface SetInterface {
    /**
     * Add item to set
     * @param mixed $Item adding item
     * @return bool result of adding item
     */
    public function add($Item);

    /**
     * Check item in set
     * @param mixed $Item checking item
     * @return bool result of check existance
     */
    public function exists($Item);
}
