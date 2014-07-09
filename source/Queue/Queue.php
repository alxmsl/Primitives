<?php
/*
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace alxmsl\Primitives\Queue;
use alxmsl\Primitives\Queue\Provider\AbstractProvider;

/**
 * Queue class
 * @author alxmsl
 * @date 7/9/14
 */ 
final class Queue implements QueueInterface {
    /**
     * @var null|AbstractProvider queue storage provider insatnce
     */
    private $Provider = null;

    /**
     * Queue storage provider setter
     * @param null|AbstractProvider $Provider queue storage provider instance
     * @return Queue self instance
     */
    public function setProvider($Provider) {
        $this->Provider = $Provider;
        return $this;
    }

    /**
     * Queue storage provider getter
     * @return null|AbstractProvider queue storage provider insatnce
     */
    public function getProvider() {
        return $this->Provider;
    }

    /**
     * Enqueue item to storage
     * @param mixed $Item queued item
     */
    public function enqueue($Item) {
        $this->getProvider()->enqueue($Item);
    }

    /**
     * Dequeque item from storage
     * @return mixed|false queued item or FALSE if queue is empty
     */
    public function dequeue() {
        return $this->getProvider()->dequeue();
    }
}
 