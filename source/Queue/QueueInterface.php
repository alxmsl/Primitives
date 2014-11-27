<?php
/*
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace alxmsl\Primitives\Queue;
use alxmsl\Primitives\Queue\Exception\EnqueueException;
use alxmsl\Primitives\Queue\Exception\DequeueException;

/**
 * Queue primitive interface
 * @author alxmsl
 * @date 7/9/14
 */ 
interface QueueInterface {
    /**
     * Enqueue item
     * @param mixed $Item queued item
     * @throws EnqueueException when queue instance was not available
     */
    public function enqueue($Item);

    /**
     * Dequeque item
     * @throws DequeueException when queue instance was not available
     * @return mixed|false queued item or FALSE if queue is empty
     */
    public function dequeue();

    /**
     * Get queue size
     * @return int queue size
     */
    public function getSize();
}
 