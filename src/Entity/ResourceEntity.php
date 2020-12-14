<?php


namespace Fwk\Entity;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\ResultStatement;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Query\QueryBuilder;
use DomainException;
use Laminas\Filter\StringTrim;
use Laminas\Filter\StripTags;
use Laminas\InputFilter\InputFilter;
use Laminas\InputFilter\InputFilterAwareInterface;
use Laminas\InputFilter\InputFilterInterface;
use Laminas\Validator\Digits;
use Laminas\Validator\Regex;
use RuntimeException;

class ResourceEntity extends BaseEntity implements InputFilterAwareInterface
{
    /**
     * @var Connection|null
     */
    protected $db;

    protected string $tableName = 'fwk_ack_resource';

    /**
     * @var array
     */
    protected array $__data = [
        'id_acl_resource' => null,
        'name' => null,
        'type' => null,
        'module' => null,
        'controller' => null,
        'action' => null
    ];
    /**
     * @var InputFilterInterface
     */
    protected $inputFilter;

    public function __construct($db = null)
    {
        $this->db = $db;
    }

    public function load(int $id)
    {
        $option[] = [
            'field' => 'id_acl_resource',
            'value' => $id
        ];

        $queryBuilder = $this->getQueryBuilder($option);

        $statement = $queryBuilder->execute();

        $data = $statement->fetch();

        if (!$data)
            throw new DomainException('Data doesn\'t exist');

        $this->setAclData($data['name']);

        $this->__data['id_acl_resource'] = $data['id_acl_resource'];
    }

    public function setAclData(string $name)
    {
        $this->__data['name'] = $name;

        $parts = explode('.', $name);

        $this->__data['type'] = array_shift($parts);
        switch ($this->__data['type']) {
            case 'mvc':
                $module = array_shift($parts);
                if ($module)
                    $this->__data['module'] = $module;

                $controller = array_shift($parts);
                if ($controller)
                    $this->__data['controller'] = $controller;

                $action = array_shift($parts);
                if ($action)
                    $this->__data['action'] = $action;

                break;
            default:
                throw new RuntimeException('Only \'MVC\' type accepted');
        }
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        $this->inputFilter = $inputFilter;
    }

    public function getInputFilter()
    {
        if ($this->inputFilter === null) {
            $inputFilter = new InputFilter();

            $inputFilter->add([
                'name' => 'id_acl_resource',
                'filters' => [
                    new StringTrim()
                ],
                'validators' => [
                    new Digits()
                ]
            ]);

            $inputFilter->add([
                'name' => 'type',
                'filters' => [
                    new StripTags(),
                    new StringTrim()
                ]
            ]);

            $inputFilter->add([
                'name' => 'module',
                'filters' => [
                    new StripTags(),
                    new StringTrim()
                ],
                'validators' => [
                    new Regex([
                        'pattern' => '/^[a-z]{1}[a-z-]*$/',
                        'message' => 'Only lowercase letters and underscore accepted'
                    ])
                ]
            ]);

            $inputFilter->add([
                'name' => 'controller',
                'required' => false,
                'filters' => [
                    new StripTags(),
                    new StringTrim()
                ],
                'validators' => [
                    new Regex([
                        'pattern' => '/^[a-z]{1}[0-9a-z -]$/',
                        'message' => 'Only lowercase letters, numbers and dashes accepted'
                    ])
                ]
            ]);

            $inputFilter->add([
                'name' => 'action',
                'required' => false,
                'filters' => [
                    new StripTags(),
                    new StringTrim()
                ],
                'validators' => [
                    new Regex([
                        'pattern' => '/^[a-z]{1}[0-9a-z -]*$/',
                        'message' => 'Only lowercase, numbers and dashes accepted'
                    ])
                ]
            ]);

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

    /**
     * @param $newData
     */
    public function exchangeArray($newData)
    {
        foreach ($newData as $key => $value) {
            if (array_key_exists($key, $this->__data)) {
                $this->__data[$key] = $value;
            }
        }

        switch ($this->__data['type']) {
            case 'mvc':
                $parts = [
                    $this->__data['type'],
                    $this->__data['module'],
                ];
                if (isset($this->__data['controller']) && !empty($this->__data['controller'])) {
                    $parts[] = $this->__data['controller'];
                    if (isset($this->__data['action']) && !empty($this->__data['action'])) {
                        $parts[] = $this->__data['action'];
                    }
                }

                $this->__data['name'] = implode('.', $parts);
                break;
            default:
                throw new RuntimeException('Only \'MVC\' type accepted');
        }
    }

    /**
     * @return int
     * @throws Exception
     */
    public function save(): int
    {
        return $this->__data['id_acl_resource'] ? $this->update() : $this->insert();
    }

    /**
     * @throws Exception
     */
    public function delete()
    {
        $result = $this->db->delete($this->tableName, [
            'id_acl_resource' => $this->__data['id_acl_resource'],
        ]);

        if (!$result) {
            throw new DomainException('Impossible to delete data');
        }

        return $result;
    }

    /**
     * @return int
     * @throws Exception
     */
    public function deleteGroupResources(): int
    {
        $result = $this->db->delete('oft_acl_role_resource', [
            'id_acl_resource' => $this->__data['id_acl_resource']
        ]);

        if (!$result)
            throw new DomainException('Impossible to delete data');

        return $result;
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function hasResources(): bool
    {
        $option[] = [
            'field' => 'name',
            'value' => $this->__data['name']
        ];

        $queryBuilder = $this->getQueryBuilder($option);

        $user = $queryBuilder->execute()->fetch();

        if (!$user)
            return false;

        return true;
    }

    /**
     * @param array $whereOptions
     * @return QueryBuilder
     */
    public function getQueryBuilder(array $whereOptions = []): QueryBuilder
    {
        $queryBuilder = $this->db->createQueryBuilder();
        $queryBuilder->select('id_acl_resource', 'name')
            ->from($this->tableName, 'r');

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

        $queryBuilder->orderBy('name');

        return $queryBuilder;
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
     * @return int
     * @throws Exception
     */
    public function insert(): int
    {
        $data = [
            'name' => $this->__data['name']
        ];

        $result = $this->db->insert($this->tableName, $data);

        if (!$result)
            throw new DomainException('Impossible to add data');

        return $result;
    }

    public function update()
    {
        $queryBuilder = $this->db->createQueryBuilder();

        $queryBuilder->update($this->tableName)
            ->set('name', ':name')
            ->where('id_acl_resource = :id_acl_resource');

        $queryBuilder->setParameters($this->__data);
        $result = $queryBuilder->execute();

        if (!$result)
            throw new DomainException('Impossible to modify data');

        return $result;
    }
}
