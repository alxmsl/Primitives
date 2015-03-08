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
use alxmsl\Primitives\Cache\Item;
use Predis\Client;
use Predis\Transaction\AbortedMultiExecException;

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
     * @param null|Client $Client predis instance client
     * @return $this provider instance
     */
    public function setClient(Client $Client) {
        $this->Client = $Client;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function get($key, $useCas = false) {
        if ($useCas) {
            $this->watched = (bool) $this->getClient()->watch($key);
        }
        $result = $this->getClient()->get($key);

        if (!is_null($result)) {
            $Item = new Item($key);
            $Item->unserialize($result);
            return $Item;
        } else {
            return new Item($key);
        }
    }

    /**
     * @inheritdoc
     * @throws CasErrorException when CAS operation was impossible
     */
    public function set($key, $Value, $useCas = false) {
        if ($useCas) {
            if ($this->watched) {
                $this->watched = false;
                try {
                    $this->getClient()->transaction()
                        ->set($key, $Value->serialize())
                        ->execute();
                } catch (AbortedMultiExecException $Ex) {
                    throw new CasErrorException('watched key was changed');
                }
            } else {
                throw new CasErrorException('key is not watching now');
            }
        } else {
            $this->getClient()->set($key, $Value->serialize());
        }
    }

    /**
     * @inheritdoc
     */
    public function remove($key) {
        $this->getClient()->del([$key]);
    }
}
