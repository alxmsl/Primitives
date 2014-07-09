<?php
/*
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace alxmsl\Primitives\Set;

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
