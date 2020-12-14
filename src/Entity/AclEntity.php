<?php


namespace Fwk\Entity;

use Doctrine\DBAL\Driver\ResultStatement;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Portability\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use DomainException;
use Laminas\Filter\StringTrim;
use Laminas\InputFilter\InputFilter;
use Laminas\InputFilter\InputFilterAwareInterface;
use Laminas\InputFilter\InputFilterInterface;
use Laminas\Validator\Digits;

class AclEntity extends BaseEntity implements InputFilterAwareInterface
{

    /**
     * @var Connection|null
     */
    protected $db;

    /**
     * @var string
     */
    protected string $tableName = 'fwk_acl_role_resource';

    /**
     * @var array
     */
    protected array $__data = [
        'id_acl_role' => null,
        'id_acl_resource' => null
    ];

    /**
     * @var InputFilterInterface
     */
    protected InputFilterInterface $inputFilter;

    public function __construct($db = null)
    {
        $this->db = $db;
    }

    public function load(int $resourceId, int $groupId)
    {
        $option = [
            [
                'field' => 'id_acl_resource',
                'value' => $resourceId
            ],
            [
                'field' => 'id_acl_role',
                'value' => $groupId
            ]
        ];

        $queryBuilder = $this->getQueryBuilder($option);

        $statement = $queryBuilder->execute();

        $this->__data = $statement->fetch();

        if ($this->__data === false) {
            throw new DomainException("Data doesn't exists");
        }
    }

    /**
     * @param InputFilterInterface $inputFilter
     */
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        $this->inputFilter = $inputFilter;
    }

    /**
     * @return InputFilterInterface
     */
    public function getInputFilter()
    {
        if ($this->inputFilter === null) {
            $inputFilter = new InputFilter();

            $inputFilter->add([
                'name' => 'id_acl_role',
                'filters' => [
                    new StringTrim()
                ],
                'validators' => [
                    new Digits()
                ]
            ]);

            $inputFilter->add([
                'name' => 'id_acl_resource',
                'filters' => [
                    new StringTrim()
                ],
                'validators' => [
                    new Digits()
                ]
            ]);

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

    /**
     * @return array
     */
    public function getArrayCopy(): array
    {
        return $this->__data;
    }

    /**
     * @return int
     * @throws Exception
     */
    public function insert(): int
    {
        $data = [
            'id_acl_role' => $this->__data['id_acl_role'],
            'id_acl_resource' => $this->__data['id_acl_resource']
        ];

        $result = $this->db->insert($this->tableName, $data);

        if ($result === false) {
            throw new DomainException('Impossible to add data');
        }

        return $result;
    }

    /**
     * @return int
     * @throws Exception
     */
    public function delete(): int
    {
        $result = $this->db->delete($this->tableName, [
            'id_acl_role' => $this->__data['id_acl_role'],
            'id_acl_resource' => $this->__data['id_acl_resource']
        ]);

        if ($result === false) {
            throw new DomainException('Impossible to delete data');
        }

        return $result;
    }

    /**
     * @param array $filters
     * @return ResultStatement|int
     * @throws Exception
     */
    public function fetchAll(array $filters = [])
    {
        $queryBuilder = $this->getQueryBuilder($filters);

        return $queryBuilder->execute();
    }

    /**
     * @param array $whereOptions
     * @return QueryBuilder
     */
    public function getQueryBuilder(array $whereOptions = []): QueryBuilder
    {
        $queryBuilder = $this->db->createQueryBuilder();
        $queryBuilder->select('id_acl_resource', 'id_acl_role')
            ->from($this->tableName, 'rr');

        $where = false;
        foreach ($whereOptions as $data) {
            if (!isset($data['operator'])) {
                $data['operator'] = '=';
            }

            if ($data['operator'] === 'LIKE') {
                $data['value'] = '%' . $data['value'] . '%';
            }

            $sqlWhere = $data['field'] . ' ' . $data['operator'] . ' :' . $data['field'];

            if ($where) {
                $queryBuilder->andWhere($sqlWhere);
            } else {
                $queryBuilder->where($sqlWhere);
                $where = true;
            }

            $queryBuilder->setParameter($data['field'], $data['value']);
        }

        return $queryBuilder;
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function hasAcl(): bool
    {
        $queryBuilder = $this->db->createQueryBuilder();

        $queryBuilder->select('id_acl_resource')
            ->from($this->tableName, 'r')
            ->where('id_acl_resource = :id_acl_resource')
            ->andWhere('id_acl_role = :id_acl_role')
            ->setParameter('id_acl_resource', $this->__data['id_acl_resource'])
            ->setParameter('id_acl_role', $this->__data['id_acl_role']);

        $user = $queryBuilder->execute()->fetch();

        if (!$user)
            return false;

        return true;
    }

}