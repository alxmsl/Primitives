<?php
/*
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace alxmsl\Primitives\Queue\Pool;
use alxmsl\Primitives\Pool\AbstractPool;
use alxmsl\Primitives\Pool\Exception\UnavailableInstanceException;
use alxmsl\Primitives\Pool\Signal\EmptyInstanceSignal;
use alxmsl\Primitives\Pool\Signal\NotEmptyInstanceSignal;
use alxmsl\Primitives\Queue\Exception\DequeueException;
use alxmsl\Primitives\Queue\Exception\EnqueueException;
use alxmsl\Primitives\Queue\Queue;
use alxmsl\Primitives\Queue\QueueInterface;

/**
 * Queues pool
 * @author alxmsl
 * @date 8/5/14
 */ 
final class Pool extends AbstractPool implements QueueInterface {
    /**
     * Add queue to pool
     * @param Queue $Queue added queue instance
     * @return $this self instance
     */
    public function addQueue(Queue $Queue) {
        $this->getInstance()->addInstance($Queue);
        return $this;
    }

    /**
     * Enqueue item
     * @param mixed $Item queued item
     * @throws EnqueueException when queue instance was not available
     */
    public function enqueue($Item) {
        foreach($this->getInstance()->getGenerator() as $Queue) {
            try {
                /** @var Queue $Queue */
                $Queue->enqueue($Item);
                return;
            } catch (EnqueueException $Ex) {
                $Exception = new UnavailableInstanceException();
                $Exception->setInstanceId($Queue->getId());
                $this->getInstance()->getGenerator()->throw($Exception);
            }
        }
    }

    /**
     * Dequeue item
     * @throws DequeueException when queue instance was not available
     * @return mixed|false queued item or FALSE if queue is empty
     */
    public function dequeue() {
        foreach ($this->getInstance()->getGenerator() as $Queue) {
            try {
                /** @var Queue $Queue */
                $Item = $Queue->dequeue();
                if ($Item !== false) {
                    $this->getInstance()->getGenerator()->send(new NotEmptyInstanceSignal($Queue));
                    return $Item;
                } else {
                    $this->getInstance()->getGenerator()->send(new EmptyInstanceSignal($Queue));
                }
            } catch (DequeueException $Ex) {
                $Exception = new UnavailableInstanceException();
                $Exception->setInstanceId($Queue->getId());
                $this->getInstance()->getGenerator()->throw($Exception);
            }
        }
        return false;
    }
}
 