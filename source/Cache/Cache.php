<?php

namespace alxmsl\Primitives\Cache;
use alxmsl\Primitives\Cache\Provider\ProviderInterface;
use alxmsl\Primitives\Cache\Traits\CacheTrait;

/**
 * Base cache class
 * @author alxmsl
 * @date 8/28/14
 */ 
class Cache implements CacheInterface {
    use CacheTrait;

    /**
     * @var null|ProviderInterface storage provider instance
     */
    private $Provider = null;

    /**
     * Storage provider setter
     * @param null|ProviderInterface $Provider storage provider instance
     * @return Cache self instance
     */
    public function setProvider(ProviderInterface $Provider) {
        $this->Provider = $Provider;
        return $this;
    }

    /**
     * Storgae provider getter
     * @return null|ProviderInterface storage provider getter
     */
    public function getProvider() {
        return $this->Provider;
    }

    /**
     * @param string $name cache key
     */
    public function __construct($name) {
        $this->name = (string) $name;
    }

    /**
     * Load cache data
     * @param bool $useCas use transaction for loading or not
     */
    protected function load($useCas = false) {
        if (is_null(self::$Value)) {
            self::$Value = $this->getProvider()->get($this->name, $useCas);
        }
    }

    /**
     * Save cache data
     * @param bool $useCas use transaction or not
     */
    protected function save($useCas = false) {
        is_null(self::$Value)
            ? $this->getProvider()->remove($this->name)
            : $this->getProvider()->set($this->name, self::$Value, $useCas);
    }

    /**
     * Clear cache data
     */
    protected function clear() {}
}
