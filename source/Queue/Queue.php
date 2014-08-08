<?php
/*
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace alxmsl\Primitives\Queue;
use alxmsl\Primitives\Pool\IdentificationInterface;
use alxmsl\Primitives\Queue\Provider\AbstractProvider;
use alxmsl\Primitives\Queue\Exception\DequeueException;
use alxmsl\Primitives\Queue\Exception\EnqueueException;
use IteratorAggregate;
use LogicException;
use Traversable;

/**
 * Queue class
 * @author alxmsl
 * @date 7/9/14
 */ 
final class Queue implements QueueInterface, IdentificationInterface, IteratorAggregate {
    /**
     * @var null|AbstractProvider queue storage provider insatnce
     */
    private $Provider = null;

    /**
     * Queue storage provider setter
     * @param null|AbstractProvider $Provider queue storage provider instance
     * @return Queue self instance
     */
    public function setProvider(AbstractProvider $Provider) {
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
     * Queue iterator getter
     * @return Traversable queue`s iterator instance
     * @throws LogicException when queue provider did not set
     */
    public function getIterator() {
        if ($this->Provider instanceof AbstractProvider) {
            return $this->getProvider()->getIterator();
        } else {
            throw new LogicException('Queue provider did not set');
        }
    }

    /**
     * Instance identificator getter
     * @return string instance identificator
     */
    public function getId() {
        return $this->getProvider()->getName();
    }

    /**
     * Enqueue item
     * @param mixed $Item queued item
     * @throws EnqueueException when queue instance was not available
     */
    public function enqueue($Item) {
        $this->getProvider()->enqueue($Item);
    }

    /**
     * Dequeque item
     * @throws DequeueException when queue instance was not available
     * @return mixed|false queued item or FALSE if queue is empty
     */
    public function dequeue() {
        return $this->getProvider()->dequeue();
    }
}
 