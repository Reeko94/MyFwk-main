<?php


namespace Fwk\src\Util;


use Fwk\Util\Arrays;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class ArraysTest extends TestCase
{

    public function testMergeConfig()
    {
        $array1 = ['var' => 'value'];
        $array2 = ['var' => 'value2', 'var2' => 'value'];

        $config = Arrays::mergeConfig($array1, $array2);

        $this->assertArrayHasKey('var', $config);
        $this->assertArrayHasKey('var2', $config);
        $this->assertEquals('value2', $config['var']);
        $this->assertEquals('value', $config['var2']);
    }

    public function testMergeConfigRecursive()
    {
        $array1 = [
            'var' => ['value'],
            'var3' => [
                'key' => 'value'
            ],
            'var4' => [
                'key' => [
                    'key' => 'value'
                ],
                'key2' => [
                    'value'
                ]
            ]
        ];

        $array2 = [
            'var' => ['value2'],
            'var2' => 'value',
            'var3' => [
                'key' => 'value2',
                'key2' => 'value'
            ],
            'var4' => [
                'key' => [
                    'key' => 'value2'
                ],
                'key2' => [
                    'value2'
                ]
            ]
        ];

        $config = Arrays::mergeConfig($array1, $array2);

        $this->assertIsArray($config);
        $this->assertIsArray($config['var']);
        $this->assertContains('value', $config['var']);
        $this->assertContains('value2', $config['var']);
        $this->assertSame('value', $config['var2']);
        $this->assertIsArray($config['var3']);
        $this->assertSame('value2', $config['var3']['key']);
        $this->assertSame('value', $config['var3']['key2']);
        $this->assertIsArray($config['var4']);
        $this->assertIsArray($config['var4']['key']);
        $this->assertSame('value2', $config['var4']['key']['key']);
        $this->assertIsArray($config['var4']['key2']);
        $this->assertContains('value', $config['var4']['key2']);
        $this->assertContains('value2', $config['var4']['key2']);
    }

    public function testMergeConfigMustThrowsException()
    {
        $this->expectException(RuntimeException::class);

        $array1 = [
            'var' => 'value'
        ];

        $array2 = [
            'var' => ['value2']
        ];

        Arrays::mergeConfig($array1, $array2);
    }

    public function testItemCompareCallbackBefore()
    {
        $item1 = ['position' => 1];
        $item2 = ['position' => 2];

        $this->assertSame(-1, Arrays::itemCompareCallback($item1, $item2));
    }

    public function testItemCompareCallbackSame()
    {

        $item1 = ['position' => 1];
        $item2 = ['position' => 1];

        $this->assertSame(0, Arrays::itemCompareCallback($item1, $item2));
    }

    public function testItemCompareCallbackAfter()
    {

        $item1 = ['position' => 2];
        $item2 = ['position' => 1];

        $this->assertSame(1, Arrays::itemCompareCallback($item1, $item2));
    }

}