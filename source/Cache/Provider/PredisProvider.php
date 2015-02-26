<?php
/*
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace alxmsl\Primitives\Cache\Provider;
use alxmsl\Primitives\Cache\Exception\CasErrorException;
use Predis\Client;
use Predis\Transaction\AbortedMultiExecException;
use Predis\Transaction\MultiExec;
use alxmsl\Primitives\Cache\Item;

/**
 * Predis provider for cache support
 * @author alxmsl
 */
final class PredisProvider implements ProviderInterface {
    /**
     * @var null|Client predis instance client
     */
    private $Client = null;

    /**
     * @var bool has CAS transaction
     */
    private $watched = false;

    /**
     * @return null|Client predis instance client
     */
    public function getClient() {
        return $this->Client;
    }

    /**
     * @param null|Client $Client predi instance client
     * @return null
     */
    public function setClient(Client $Client) {
        $this->Client = $Client;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function get($key, $useCas) {
        if ($useCas) {
            $this->getClient()->watch($key);
            $this->watched = true;
            $result = $this->getClient()->get($key);
        } else {
            $result = $this->getClient()->get($key);
        }
        if (is_null($result)) {
            return new Item($key);
        } else {
            return unserialize($result);
        }
    }

    /**
     * @inheritdoc
     */
    public function set($key, $value, $useCas) {
        if ($useCas && $this->watched) {
            $this->watched = false;

            try {
                $this->getClient()->transaction()
                    ->set($key, serialize($value))
                    ->execute();
            } catch (AbortedMultiExecException $Ex) {
                throw new CasErrorException();
            }
        } else {
            $this->getClient()->set($key, serialize($value));
        }
    }

    /**
     * @inheritdoc
     */
    public function remove($key) {
        $this->getClient()->del([$key]);
    }
}
