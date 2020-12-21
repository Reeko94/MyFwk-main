<?php


namespace Fwk\Cache\Traits;

use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\InvalidArgumentException;

/**
 * Trait CommonAdapterTrait
 * @package Fwk\Cache\Traits
 */
trait CommonAdapterTrait
{
    /**
     * @return CacheItemPoolInterface
     */
    public function getAdapterObject(): CacheItemPoolInterface
    {
        return $this->adapter;
    }

    /**
     * @param $key
     * @return CacheItemInterface
     */
    public function getItem(array $key): CacheItemInterface
    {
        return $this->adapter->getItem($key);
    }

    /**
     * @param array $keys
     * @return CacheItemInterface[]
     * @throws InvalidArgumentException
     */
    public function getItems(array $keys = [])
    {
        return $this->getAdapterObject()->getItems($keys);
    }

    /**
     * @param CacheItemInterface $item
     * @return bool
     */
    public function save(CacheItemInterface $item): bool
    {
        return $this->getAdapterObject()->save($item);
    }

    /**
     * @return bool
     */
    public function commit(): bool
    {
        return $this->getAdapterObject()->commit();
    }

    /**
     * @return bool
     */
    public function clear(): bool
    {
        return $this->getAdapterObject()->clear();
    }

    /**
     * @param string $key
     * @return bool
     * @throws InvalidArgumentException
     */
    public function deleteItem(string $key): bool
    {
        return $this->getAdapterObject()->deleteItem($key);
    }
}
