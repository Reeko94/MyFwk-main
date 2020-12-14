<?php


namespace Fwk\Tests\Db;

use Fwk\Db\NoDbConnection;
use PHPUnit\Framework\TestCase;

class NoDbConnectionTest extends TestCase
{
    protected NoDbConnection $noDb;

    protected function setUp(): void
    {
        $this->noDb = new NoDbConnection();
    }

    public function testBeginTransaction()
    {
        $this->expectException('RuntimeException');
        $this->expectExceptionMessage('Not implemented');
        $this->noDb->beginTransaction();
    }

    public function tesCommit()
    {
        $this->expectException('RuntimeException');
        $this->expectExceptionMessage('Not implemented');
        $this->noDb->commit();
    }

    public function testErrorCode()
    {
        $this->expectException('RuntimeException');
        $this->expectExceptionMessage('Not implemented');
        $this->noDb->errorCode();
    }

    public function testErrorInfo()
    {
        $this->expectException('RuntimeException');
        $this->expectExceptionMessage('Not implemented');
        $this->noDb->errorInfo();
    }

    public function testExec()
    {
        $this->expectException('RuntimeException');
        $this->expectExceptionMessage('Not implemented');
        $this->noDb->exec('statement');
    }

    public function testLastInsertId()
    {
        $this->expectException('RuntimeException');
        $this->expectExceptionMessage('Not implemented');
        $this->noDb->lastInsertId();
    }

    public function testPrepare()
    {
        $this->expectException('RuntimeException');
        $this->expectExceptionMessage('Not implemented');
        $this->noDb->prepare('query');
    }

    public function testQuery()
    {
        $this->expectException('RuntimeException');
        $this->expectExceptionMessage('Not implemented');
        $this->noDb->query('query');
    }

    public function testQuote()
    {
        $this->expectException('RuntimeException');
        $this->expectExceptionMessage('Not implemented');
        $this->noDb->quote('some input');
    }

    public function testRollBack()
    {
        $this->expectException('RuntimeException');
        $this->expectExceptionMessage('Not implemented');
        $this->noDb->rollBack();
    }

    public function testCreateQueryBuilder()
    {
        $this->expectException('RuntimeException');
        $this->expectExceptionMessage('Not implemented');
        $this->noDb->createQueryBuilder();
    }
}
