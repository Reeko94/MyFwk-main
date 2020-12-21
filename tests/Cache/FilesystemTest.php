<?php


namespace Fwk\Tests\Cache;

use Fwk\Cache\Filesystem;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class FilesystemTest extends TestCase
{
    public function testConstruct()
    {
        $options = [
            'directory' => sys_get_temp_dir()
        ];

        $adapter = new Filesystem($options);
        $adapterObject = $adapter->getAdapterObject();
        $this->assertInstanceOf(FilesystemAdapter::class, $adapterObject);
    }

    public function testConstructWithInvalidDirectory()
    {
        $this->expectException(RuntimeException::class);

        $adapter = new Filesystem(/* No options, no directory */);
    }

    public function testConstructWithOptions()
    {
        $options = [
            'directory' => sys_get_temp_dir(),
            'namespace' => 'test',
            'defaultLifetime' => 0
        ];

        $adapter = new Filesystem($options);
        $adapterObject = $adapter->getAdapterObject();

        $this->assertInstanceOf(FilesystemAdapter::class, $adapterObject);
    }
}
