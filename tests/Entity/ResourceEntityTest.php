<?php


namespace Fwk\Tests\Entity;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\Statement;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Query\QueryBuilder;
use DomainException;
use Fwk\Entity\ResourceEntity;
use Laminas\InputFilter\InputFilterInterface;
use Mockery;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class ResourceEntityTest extends TestCase
{
    public function testDefaultCreate()
    {
        $entity = new ResourceEntity();
        $data = $entity->getArrayCopy();


        $this->assertArrayHasKey('id_acl_resource', $data);
        $this->assertArrayHasKey('name', $data);
        $this->assertArrayHasKey('type', $data);
        $this->assertArrayHasKey('controller', $data);
        $this->assertArrayHasKey('module', $data);
        $this->assertArrayHasKey('action', $data);
    }

    public function testLoad()
    {
        $queryBuilder = $this->getQueryBuilder([
            'id_acl_resource' => 1,
            'name' => 'mvc.module.controller.action'
        ]);

        $connection = Mockery::mock(Connection::class);
        $connection->shouldReceive('createQueryBuilder')
            ->withNoArgs()
            ->andReturn($queryBuilder);

        $entity = new ResourceEntity($connection);
        $entity->load(1);
        $data = $entity->getArrayCopy();

        $this->assertEquals(1, $data['id_acl_resource']);
        $this->assertEquals('mvc.module.controller.action', $data['name']);
        $this->assertEquals('mvc', $data['type']);
        $this->assertEquals('module', $data['module']);
        $this->assertEquals('controller', $data['controller']);
        $this->assertEquals('action', $data['action']);
    }

    public function testLoadFailed()
    {
        $this->expectException(DomainException::class);
        $queryBuilder = $this->getQueryBuilder(false);

        $connection = Mockery::mock(Connection::class);
        $connection->shouldReceive('createQueryBuilder')
            ->withNoArgs()
            ->andReturn($queryBuilder);

        $entity = new ResourceEntity($connection);
        $entity->load(1);
    }

    public function testInputFiltersAreSetCorrectly()
    {
        $entity = new ResourceEntity();
        $inputFilter = $entity->getInputFilter();

        $this->assertSame(5, $inputFilter->count());
        $this->assertTrue($inputFilter->has('id_acl_resource'));
        $this->assertTrue($inputFilter->has('type'));
        $this->assertTrue($inputFilter->has('module'));
        $this->assertTrue($inputFilter->has('controller'));
        $this->assertTrue($inputFilter->has('action'));
    }

    public function testSetInputFilter()
    {
        $mockInputFilter = Mockery::mock(InputFilterInterface::class);

        $entity = new ResourceEntity();
        $entity->setInputFilter($mockInputFilter);
        $inputFilter = $entity->getInputFilter();

        $this->assertEquals($mockInputFilter, $inputFilter);
    }

    public function testExchangeArray()
    {
        $data = [
            'id_acl_resource' => 2,
            'type' => 'mvc',
            'module' => 'module',
            'controller' => 'controller',
            'action' => 'action'
        ];

        $entity = new ResourceEntity();
        $entity->exchangeArray($data);
        $entityData = $entity->getArrayCopy();

        $this->assertEquals(2, $entityData['id_acl_resource']);
        $this->assertEquals('mvc.module.controller.action', $entityData['name']);
    }

    public function testExchangeArrayException()
    {
        $this->expectException(RuntimeException::class);

        $data = [
            'type' => 'mvcError'
        ];

        $entity = new ResourceEntity();
        $entity->exchangeArray($data);
    }

    public function testInsert()
    {
        $data = [
            'type' => 'mvc',
            'module' => 'module',
            'controller' => 'controller',
            'action' => 'action'
        ];

        $connection = Mockery::mock(Connection::class);
        $connection->shouldReceive('insert')
            ->andReturn(true);

        $entity = new ResourceEntity($connection);
        $entity->exchangeArray($data);
        $this->assertEquals(1, $entity->insert());
    }

    public function testFailedInsert()
    {
        $this->expectException(DomainException::class);
        $data = [
            'type' => 'mvc',
            'module' => 'module',
            'controller' => 'controller',
            'action' => 'action'
        ];

        $connection = Mockery::mock(Connection::class);
        $connection->shouldReceive('insert')
            ->andReturn(false);

        $entity = new ResourceEntity($connection);
        $entity->exchangeArray($data);
        $entity->insert();
    }

    public function testUpdate()
    {
        $data = [
            'type' => 'mvc',
            'module' => 'module',
            'controller' => 'controller',
            'action' => 'action',
            'name' => 'mvc.module.controller.action',
            'id_acl_resource' => 1
        ];

        $queryBuilder = $this->getQueryBuilder($data);
        $queryBuilder->shouldReceive('update')
            ->andReturn($queryBuilder);
        $queryBuilder->shouldReceive('set')
            ->andReturn($queryBuilder);
        $queryBuilder->shouldReceive('where')
            ->andReturn($queryBuilder);
        $queryBuilder->shouldReceive('setParameters')
            ->with($data)
            ->andReturn($queryBuilder);
        $queryBuilder->shouldReceive('execute')
            ->once()
            ->andReturn(true);

        $connection = Mockery::mock(Connection::class);
        $connection->shouldReceive('createQueryBuilder')
            ->withNoArgs()
            ->andReturn($queryBuilder);

        $entity = new ResourceEntity($connection);
        $entity->load(1);
        $this->assertEquals(1, $entity->save());
    }

    /**
     * @throws Exception
     */
    public function testUpdateFailed()
    {
        $this->expectException(DomainException::class);
        $data = [
            'type' => 'mvc',
            'module' => 'module',
            'controller' => 'controller',
            'action' => 'action',
            'name' => 'mvc.module.controller.action',
            'id_acl_resource' => 1
        ];

        $queryBuilder = $this->getQueryBuilder($data);
        $queryBuilder->shouldReceive('update')
            ->andReturn($queryBuilder);
        $queryBuilder->shouldReceive('set')
            ->andReturn($queryBuilder);
        $queryBuilder->shouldReceive('where')
            ->andReturn($queryBuilder);
        $queryBuilder->shouldReceive('setParameters')
            ->with($data)
            ->andReturn($queryBuilder);
        $queryBuilder->shouldReceive('execute')
            ->once()
            ->andReturn(false);

        $connection = Mockery::mock(Connection::class);
        $connection->shouldReceive('createQueryBuilder')
            ->withNoArgs()
            ->andReturn($queryBuilder);

        $entity = new ResourceEntity($connection);
        $entity->load(1);
        $entity->save();
    }

    public function testDelete()
    {
        $data = [
            'id_acl_resource' => 1,
            'name' => 'mvc.module.controller.action'
        ];

        $queryBuilder = $this->getQueryBuilder($data);

        $connection = Mockery::mock(Connection::class);
        $connection->shouldReceive('createQueryBuilder')
            ->withNoArgs()
            ->andReturn($queryBuilder);
        $connection->shouldReceive('delete')
            ->andReturn(1);

        $entity = new ResourceEntity($connection);
        $entity->load(1);
        $this->assertEquals(1, $entity->delete());
    }

    public function testDeleteGroupResources()
    {
        $data = [
            'id_acl_resource' => 1,
            'name' => 'mvc.module.controller.action'
        ];

        $queryBuilder = $this->getQueryBuilder($data);

        $connection = Mockery::mock(Connection::class);
        $connection->shouldReceive('createQueryBuilder')
            ->withNoArgs()
            ->andReturn($queryBuilder);
        $connection->shouldReceive('delete')
            ->withArgs(['oft_acl_role_resource', ['id_acl_resource' => 1]])
            ->andReturn(1);

        $entity = new ResourceEntity($connection);
        $entity->load(1);
        $this->assertEquals(1, $entity->deleteGroupResources());
    }


    protected function getQueryBuilder($param)
    {
        $statement = Mockery::mock(Statement::class);
        $statement->shouldReceive('fetch')
            ->once()
            ->withNoArgs()
            ->andReturn($param);

        $queryBuilder = Mockery::mock(QueryBuilder::class);
        $queryBuilder->shouldReceive('select')
            ->once()
            ->andReturn($queryBuilder);
        $queryBuilder->shouldReceive('select')
            ->once()
            ->andReturn($queryBuilder);
        $queryBuilder->shouldReceive('from')
            ->once()
            ->andReturn($queryBuilder);
        $queryBuilder->shouldReceive('where')
            ->andReturn($queryBuilder);
        $queryBuilder->shouldReceive('setParameter')
            ->once()
            ->andReturn($queryBuilder);
        $queryBuilder->shouldReceive('orderBy')
            ->once()
            ->andReturn($queryBuilder);
        $queryBuilder->shouldReceive('execute')
            ->once()
            ->andReturn($statement);

        return $queryBuilder;
    }
}
