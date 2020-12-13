<?php


namespace Fwk\Db;

use Doctrine\DBAL\Driver\Result;
use Doctrine\DBAL\Driver\Statement;
use Doctrine\DBAL\ParameterType;
use RuntimeException;

/**
 * Class NoDbConnection
 * @package Fwk\Db
 */
class NoDbConnection implements \Doctrine\DBAL\Driver\Connection
{
    /**
     * @param string $sql
     * @return Statement
     */
    public function prepare(string $sql): Statement
    {
        throw new RuntimeException('Not implemented');
    }

    /**
     * @param string $sql
     * @return Result
     */
    public function query(string $sql): Result
    {
        throw new RuntimeException('Not implemented');
    }

    /**
     * @param mixed $value
     * @param int $type
     * @return mixed|void
     */
    public function quote($value, $type = ParameterType::STRING)
    {
        throw new RuntimeException('Not implemented');
    }

    /**
     * @param string $sql
     * @return int
     */
    public function exec(string $sql): int
    {
        throw new RuntimeException('Not implemented');
    }

    /**
     * @param null $name
     * @return string
     */
    public function lastInsertId($name = null): string
    {
        throw new RuntimeException('Not implemented');
    }

    /**
     * @return bool
     */
    public function beginTransaction(): bool
    {
        throw new RuntimeException('Not implemented');
    }

    /**
     * @return bool
     */
    public function commit(): bool
    {
        throw new RuntimeException('Not implemented');
    }

    /**
     * @return bool
     */
    public function rollBack(): bool
    {
        throw new RuntimeException('Not implemented');
    }

    public function createQueryBuilder()
    {
        throw new RuntimeException('Not implemented');
    }

    public function errorCode()
    {
        throw new RuntimeException('Not implemented');
    }

    public function errorInfo()
    {
        throw new RuntimeException('Not implemented');
    }
}
