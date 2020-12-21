<?php


namespace Fwk\Cache;

use Psr\Cache\CacheItemInterface;

interface AdapterInterface
{
    /**
     * Return a cache item
     *
     * @param array $key
     * @return CacheItemInterface
     */
    public function getItem(array $key): CacheItemInterface;

    /**
     * Return a collection of cache items
     *
     * @param array $keys
     * @return CacheItemInterface[]
     */
    public function getItems(array $keys = []);

    /**
     * Save an item in the cache
     *
     * @param CacheItemInterface $item
     * @return bool
     */
    public function save(CacheItemInterface $item): bool;

    /**
     * @return bool
     */
    public function commit(): bool;

    /**
     * @return bool
     */
    public function clear(): bool;

    /**
     * @param string $key
     * @return bool
     */
    public function deleteItem(string $key): bool;
}
