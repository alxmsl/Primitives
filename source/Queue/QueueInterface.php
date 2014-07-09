<?php
/*
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace alxmsl\Primitives\Queue;

/**
 * Queue primitive interface
 * @author alxmsl
 * @date 7/9/14
 */ 
interface QueueInterface {
    /**
     * Enqueue item
     * @param mixed $Item queued item
     */
    public function enqueue($Item);

    /**
     * Dequeque item
     * @return mixed|false queued item or FALSE if queue is empty
     */
    public function dequeue();
}
 