<?php


namespace Fwk\Tests\Entity;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\Statement;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Query\QueryBuilder;
use DomainException;
use Fwk\Entity\AclEntity;
use Laminas\InputFilter\InputFilterInterface;
use Mockery;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

class AclEntityTest extends TestCase
{
    protected $connection;

    protected function setUp(): void
    {
        $this->connection = Mockery::mock(Connection::class);
    }

    public function testDefaultCreate()
    {
        $entity = new AclEntity();
        $data = $entity->getArrayCopy();

        $this->assertArrayHasKey('id_acl_role', $data);
        $this->assertArrayHasKey('id_acl_resource', $data);
    }

    public function testLoad()
    {
        $queryBuilder = $this->getQueryBuilder([
            'id_acl_role' => 1,
            'id_acl_resource' => 2
        ]);

        $connection = Mockery::mock(Connection::class);
        $connection->shouldReceive('createQueryBuilder')
            ->withNoArgs()
            ->andReturn($queryBuilder);

        $entity = new AclEntity($connection);
        $entity->load(1, 2);
        $data = $entity->getArrayCopy();

        $this->assertEquals(1, $data['id_acl_role']);
        $this->assertEquals(2, $data['id_acl_resource']);
    }

    public function testSetInputFilter()
    {
        $mockInputFilter = Mockery::mock(InputFilterInterface::class);

        $entity = new AclEntity();
        $entity->setInputFilter($mockInputFilter);
        $inputFilter = $entity->getInputFilter();

        $this->assertEquals($mockInputFilter, $inputFilter);
    }

    public function testExchangeArray()
    {
        $data = [
            'id_acl_resource' => 2,
            'id_acl_role' => 1
        ];

        $entity = new AclEntity();
        $entity->exchangeArray($data);
        $entityData = $entity->getArrayCopy();

        $this->assertEquals(2, $entity['id_acl_resource']);
        $this->assertEquals(1, $entity['id_acl_role']);
    }

    /**
     * @throws Exception
     */
    public function testInsert()
    {
        $data = [
            'id_acl_resource' => 2,
            'id_acl_role' => 1
        ];

        $connection = Mockery::mock(Connection::class);
        $connection->shouldReceive('insert')
            ->andReturn(true);

        $entity = new AclEntity($connection);
        $entity->exchangeArray($data);
        $this->assertEquals(1, $entity->insert());
    }

    /**
     * @throws Exception
     */
    public function testFailedInsert()
    {
        $this->expectException(DomainException::class);

        $data = [
            'id_acl_resource' => 2,
            'id_acl_role' => 1
        ];

        $connection = Mockery::mock(Connection::class);
        $connection->shouldReceive('insert')
            ->andReturn(false);

        $entity = new AclEntity($connection);
        $entity->exchangeArray($data);
        $this->assertEquals(0, $entity->insert());
    }

    /**
     * @throws Exception
     */
    public function testDelete()
    {
        $data = [
            'id_acl_resource' => 2,
            'id_acl_role' => 1
        ];

        $queryBuilder = $this->getQueryBuilder($data);

        $connection = Mockery::mock(Connection::class);
        $connection->shouldReceive('createQueryBuilder')
            ->withNoArgs()
            ->andReturn($queryBuilder);
        $connection->shouldReceive('delete')
            ->andReturn(1);

        $entity = new AclEntity($connection);
        $entity->load(1, 2);
        $this->assertEquals(1, $entity->delete());
    }

    /**
     * @throws Exception
     */
    public function testDeleteFailed()
    {
        $this->expectException(DomainException::class);

        $data = [
            'id_acl_resource' => 2,
            'id_acl_role' => 1
        ];

        $queryBuilder = $this->getQueryBuilder($data);

        $connection = Mockery::mock(Connection::class);
        $connection->shouldReceive('createQueryBuilder')
            ->withNoArgs()
            ->andReturn($queryBuilder);
        $connection->shouldReceive('delete')
            ->andReturn(false);

        $entity = new AclEntity($connection);
        $entity->load(1, 2);
        $this->assertEquals(0, $entity->delete());
    }

    /**
     * @throws Exception
     */
    public function testHasAcl()
    {
        $data = [
            'id_acl_resource' => 2,
            'id_acl_role' => 1
        ];

        $queryBuilder = $this->getQueryBuilder($data);

        $connection = Mockery::mock(Connection::class);
        $connection->shouldReceive('createQueryBuilder')
            ->withNoArgs()
            ->andReturn($queryBuilder);

        $entity = new AclEntity($connection);
        $this->assertTrue($entity->hasAcl());

        $queryBuilderFalse = $this->getQueryBuilder(false);
        $connectionFalse = Mockery::mock(Connection::class);
        $connectionFalse->shouldReceive('createQueryBuilder')
            ->withNoArgs()
            ->andReturn($queryBuilderFalse);

        $entityFalse = new AclEntity($connectionFalse);
        $this->assertFalse($entityFalse->hasAcl());
    }

    /**
     * @throws Exception
     */
    public function testAll()
    {
        $statement = Mockery::mock(Statement::class);

        $queryBuilder = Mockery::mock(QueryBuilder::class);
        $queryBuilder->shouldReceive('select')
            ->once()
            ->andReturn($queryBuilder);
        $queryBuilder->shouldReceive('from')
            ->once()
            ->andReturn($queryBuilder);
        $queryBuilder->shouldReceive('execute')
            ->once()
            ->andReturn($statement);

        $connection = Mockery::mock(Connection::class);
        $connection->shouldReceive('createQueryBuilder')
            ->once()
            ->withNoArgs()
            ->andReturn($queryBuilder);

        $aclEntity = new AclEntity($connection);

        $this->assertEquals($statement, $aclEntity->fetchAll());
    }

    /**
     * @param $param
     * @return QueryBuilder|LegacyMockInterface|MockInterface
     */
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
        $queryBuilder->shouldReceive('from')
            ->once()
            ->andReturn($queryBuilder);

        $queryBuilder->shouldReceive('where')
            ->andReturn($queryBuilder);
        $queryBuilder->shouldReceive('andWhere')
            ->andReturn($queryBuilder);
        $queryBuilder->shouldReceive('setParameter')
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
