<?php


namespace Fwk\Tests\Entity;

use Fwk\Entity\BaseEntity;
use PHPUnit\Framework\TestCase;

class BaseEntityTest extends TestCase
{
    public function testExchangeArrayInStrictMode()
    {
        $baseEntity = new BaseEntity([
            'a' => 1,
            'b' => 2,
            'e' => '123456'
        ], true);

        $previous = $baseEntity->exchangeArray(['b' => 3, 'c' => 4, 'e' => '0123456']);

        $this->assertSame(['a' => 1, 'b' => 2, 'e' => '123456'], $previous);
        $this->assertSame(['a' => null, 'b' => 3, 'e' => '0123456'], $baseEntity->getArrayCopy());
    }

    public function testExchangeArrayNotInStrictMode()
    {
        $baseEntity = new BaseEntity([
            'a' => 1,
            'b' => 2
        ]);

        $previous = $baseEntity->exchangeArray(['b' => 3, 'c' => 4]);

        $this->assertSame(['a' => 1, 'b' => 2], $previous);
        $this->assertSame(['a' => null, 'b' => 3, 'c' => 4], $baseEntity->getArrayCopy());
    }

    public function testStrictModeOffsetSet()
    {
        $baseEntity = new BaseEntity(['a' => 1], true);

        $baseEntity['a'] = 2;

        $this->assertNull($baseEntity['b']);
    }

    public function testStrictModeMagicSet()
    {
        $baseEntity = new BaseEntity(['a' => 1], true);

        $baseEntity->b = 2;

        $this->assertNull($baseEntity->b);
    }

    public function testGetArrayCopy()
    {
        $baseEntity = new BaseEntity([
            'a' => 1,
            'b' => 2
        ]);
        $this->assertSame(['a' => 1, 'b' => 2], $baseEntity->getArrayCopy());
    }

    public function testOffsetExits()
    {
        $baseEntity = new BaseEntity(['a' => 1]);

        $this->assertTrue(isset($baseEntity['a']));
        $this->assertFalse(isset($baseEntity['b']));
    }

    public function testOffsetSet()
    {
        $baseEntity = new BaseEntity(['a' => 1]);
        $baseEntity['a'] = 2;
        $baseEntity['b'] = 3;

        $this->assertSame(2, $baseEntity['a']);
        $this->assertSame(3, $baseEntity['b']);
    }

    public function testOffsetUnset()
    {
        $baseEntity = new BaseEntity(['a' => 1]);
        unset($baseEntity['b']);

        $this->assertNull($baseEntity['b']);
    }

    public function testMagicGet()
    {
        $baseEntity = new BaseEntity(['a' => 1]);

        $this->assertSame(1, $baseEntity->a);
        $this->assertNull($baseEntity->b);
    }

    public function testMagicSet()
    {
        $baseEntity = new BaseEntity(['a' => 1]);

        $baseEntity->a = 2;
        $baseEntity->b = 3;

        $this->assertSame(2, $baseEntity->a);
        $this->assertSame(3, $baseEntity->b);
    }

    public function testMagicUnset()
    {
        $baseEntity = new BaseEntity(['a' => 1]);

        unset($baseEntity->a);

        $this->assertNull($baseEntity->a);
    }

    public function testMagicIsset()
    {
        $baseEntity = new BaseEntity(['a' => 1, 'b' => null]);

        $this->assertTrue(isset($baseEntity->a));
        $this->assertFalse(isset($baseEntity->b));
        $this->assertFalse(isset($baseEntity->c));
    }

    public function testGetUpdatedFieldsWithNoMod()
    {
        $baseEntity = new BaseEntity(['a' => 1, 'b' => 2]);

        $this->assertSame([], $baseEntity->getUpdatedFields());
    }

    public function testGetUpdatedFieldsWithArrayKeysExchange()
    {
        $data = ['a' => 1, 'b' => 1];
        $baseEntity = new BaseEntity($data, false);
        $baseEntityStrict = new BaseEntity($data, true);

        $newData = ['a' => 2, 'b' => 2, 'c' => 2];
        $baseEntity->exchangeArray($newData);
        $baseEntityStrict->exchangeArray($newData);

        $this->assertSame($newData, $baseEntity->getUpdatedFields(), 'Entity data not strict KO');
        $this->assertSame(['a' => 2, 'b' => 2], $baseEntityStrict->getUpdatedFields(), 'Entity data not strict KO');
    }

    public function testGetUpdatedFieldsWithMagicSet()
    {
        $baseEntity = new BaseEntity(['a' => 1, 'b' => 2]);
        $baseEntity->a = 2;
        $baseEntity->b = 2;
        $baseEntity->c = 2;

        $this->assertSame(['a' => 2, 'c' => 2], $baseEntity->getUpdatedFields());
    }

    public function testIterator()
    {
        $array = ['a' => 1, 'b' => 2];
        $baseEntity = new BaseEntity($array);

        $count = 0;
        foreach ($baseEntity as $key => $value) {
            ++$count;
            $this->assertArrayHasKey($key, $array);
            $this->assertSame($value, $array[$key]);
        }

        $this->assertEquals(2, $count);
    }

    public function testBaseEntityWithAColumnNameData()
    {
        $data = ['a' => 1, 'b' => 2, 'data' => 'data !'];
        $baseEntity = new BaseEntity($data);
        $this->assertEquals($data, $baseEntity->getArrayCopy());
    }

    public function testResetUpdateFields()
    {
        $baseEntity = new BaseEntity(['a' => 1, 'b' => 2]);

        $baseEntity->a = 2;
        $baseEntity->b = 2;
        $baseEntity->c = 2;

        $this->assertCount(2, $baseEntity->getUpdatedFields());

        $baseEntity->resetUpdatedFields();

        $this->assertCount(0, $baseEntity->getUpdatedFields());
    }

    public function testMultipleUpdates()
    {
        $e = new BaseEntity();
        $e->foo = 'bar';
        $e->foo = 'baz';
        $e->foo = 'eee';
        $this->assertEquals('eee', $e->foo);
        $this->assertEquals(['foo' => 'eee'], $e->getUpdatedFields());
    }
}
