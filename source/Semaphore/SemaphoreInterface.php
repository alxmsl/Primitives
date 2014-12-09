<?php
/*
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace alxmsl\Primitives\Semaphore;

/**
 * Semaphore interface
 * @author alxmsl
 * @date 6/17/14
 */
interface SemaphoreInterface {
    /**
     * Start waiting a semaphore method
     * @return bool semaphore waiting result
     */
    public function wait();

    /**
     * Make semaphore available again
     */
    public function signal();
}
