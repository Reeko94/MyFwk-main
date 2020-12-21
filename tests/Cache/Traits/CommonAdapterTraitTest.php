<?php


namespace Fwk\Tests\Cache\Traits;

use Fwk\Cache\Traits\CommonAdapterTrait;
use Mockery;
use PHPUnit\Framework\TestCase;
use Psr\Cache\CacheItemInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;

/**
 * Class CommonAdapter
 * @package Fwk\Tests\Cache\Traits
 */
class CommonAdapter
{
    use CommonAdapterTrait;

    public function __construct($adapterMock)
    {
        $this->adapter = $adapterMock;
    }
}

class CommonAdapterTraitTest extends TestCase
{
    public function testGetAdapterObject()
    {
        $adapterMock = Mockery::mock(AdapterInterface::class);
        $commonAdapter = new CommonAdapter($adapterMock);
        $this->assertEquals($adapterMock, $commonAdapter->getAdapterObject());
    }

    public function testGetItem()
    {
        $return = 'data';
        $key = 'itemKey';

        $adapterMock = Mockery::mock(AdapterInterface::class);
        $adapterMock->shouldReceive('getItem')
            ->with($key)
            ->once()
            ->andReturn($return);

        $commonAdapter = new CommonAdapter($adapterMock);
        $this->assertEquals($return, $commonAdapter->getItem($key));
    }

    public function testGetItems()
    {
        $return = 'data';
        $keys = ['itemKeys'];

        $adapterMock = Mockery::mock(AdapterInterface::class);
        $adapterMock->shouldReceive('getItems')
            ->with($keys)
            ->once()
            ->andReturn($return);

        $commonAdapter = new CommonAdapter($adapterMock);
        $this->assertEquals($return, $commonAdapter->getItems($keys));
    }

    public function testSave()
    {
        $return = true;

        $itemMock = Mockery::mock(CacheItemInterface::class);

        $adapterMock = Mockery::mock(AdapterInterface::class);
        $adapterMock->shouldReceive('save')
            ->with($itemMock)
            ->once()
            ->andReturn($return);

        $commonAdapter = new CommonAdapter($adapterMock);
        $this->assertEquals($return, $commonAdapter->save($itemMock));
    }

    public function testCommit()
    {
        $return = true;

        $adapterMock = Mockery::mock(AdapterInterface::class);
        $adapterMock->shouldReceive('commit')
            ->withNoArgs()
            ->once()
            ->andReturn($return);

        $commonAdapter = new CommonAdapter($adapterMock);
        $this->assertEquals($return, $commonAdapter->commit());
    }

    public function testClear()
    {
        $return = true;

        $adapterMock = Mockery::mock(AdapterInterface::class);
        $adapterMock->shouldReceive('clear')
            ->withNoArgs()
            ->once()
            ->andReturn($return);

        $commonAdapter = new CommonAdapter($adapterMock);

        $this->assertEquals($return, $commonAdapter->clear());
    }

    public function testDeleteItem()
    {
        $return = true;
        $key = 'itemKey';

        $adapterMock = Mockery::mock(AdapterInterface::class);
        $adapterMock->shouldReceive('deleteItem')
            ->with($key)
            ->once()
            ->andReturn($return);

        $commonAdapter = new CommonAdapter($adapterMock);
        $this->assertEquals($return, $commonAdapter->deleteItem($key));
    }
}
