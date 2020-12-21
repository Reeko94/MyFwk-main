<?php


namespace Fwk\Cache;

use Fwk\Cache\Traits\CommonAdapterTrait;
use RuntimeException;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

/**
 * Class Filesystem
 * @package Fwk\Cache
 */
class Filesystem implements AdapterInterface
{
    use CommonAdapterTrait;

    /**
     * @var FilesystemAdapter
     */
    protected FilesystemAdapter $adapter;

    /**
     * Filesystem constructor.
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        if (!isset($options['directory']) || !is_dir($options['directory'])) {
            throw new RuntimeException("The specified cache path doesn't exists or is not an existing directory");
        }

        $this->adapter = new FilesystemAdapter(
            isset($options['namespace']) ? $options['namespace'] : '',
            isset($options['defaultLifetime']) ? $options['defaultLifetime'] : 0
        );
    }
}
