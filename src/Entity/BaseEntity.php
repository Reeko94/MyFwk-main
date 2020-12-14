<?php


namespace Fwk\Entity;

use ArrayAccess;
use Iterator;

class BaseEntity implements ArrayAccess, Iterator
{
    /**
     * Data entity
     *
     * @var array
     */
    protected array $__data = [];

    /**
     * Array of updated fields
     * @var array
     */
    protected array $updatedFields = [];

    /**
     * If strict mode enable, only attributes defined with the construcutor can be edit
     *
     * @var bool
     */
    protected bool $strict;

    /**
     * @var mixed
     */
    protected $currentPosition;

    /**
     * @var array
     */
    protected array $keys;

    public function __construct(array $data = [], $strict = false)
    {
        $this->__data = $data;
        $this->strict = $strict;
    }

    public function exchangeArray($newData)
    {
        $currentData = $this->__data;

        $dataKeys = array_merge(
            array_keys($this->__data),
            array_keys($newData)
        );

        foreach ($dataKeys as $key) {
            $existInCurrentData = array_key_exists($key, $currentData);
            $existInNewData = array_key_exists($key, $newData);

            if ($existInCurrentData && $existInNewData && $this->__data[$key] !== $newData[$key]) {
                $this->__data[$key] = $newData[$key];
                $this->addUpdatedField($key);
            } elseif ($existInCurrentData && !$existInNewData) {
                $this->__data[$key] = null;
            } elseif (!$existInCurrentData && $existInNewData && !$this->strict) {
                $this->__data[$key] = $newData[$key];
                $this->addUpdatedField($key);
            }
        }

        return $currentData;
    }

    public function getArrayCopy(): array
    {
        return $this->__data;
    }

    /**
     * @return array
     */
    public function getUpdatedFields(): array
    {
        $result = [];
        foreach ($this->updatedFields as $field) {
            $result[$field] = $this->__data[$field];
        }

        return $result;
    }

    public function setUpdatedFields(array $updatedFields = [])
    {
        $this->updatedFields = $updatedFields;
    }

    public function resetUpdatedFields()
    {
        $this->setUpdatedFields([]);
    }

    public function addUpdatedField($name)
    {
        if (!in_array($name, $this->updatedFields)) {
            $this->updatedFields[] = $name;
        }
    }

    /**
     * @param string $name
     * @return bool
     * @inheritDoc
     */
    public function offsetExists($name): bool
    {
        return isset($this->__data[$name]);
    }

    /**
     * @inheritDoc
     */
    public function offsetGet($name)
    {
        if (array_key_exists($name, $this->__data)) {
            return $this->__data[$name];
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function offsetSet($name, $value)
    {
        if ($this->strict && !array_key_exists($name, $this->__data)) {
            return;
        }

        if (!array_key_exists($name, $this->__data) || $value != $this->__data[$name]) {
            $this->addUpdatedField($name);
        }

        $this->__data[$name] = $value;
    }

    /**
     * @inheritDoc
     */
    public function offsetUnset($name)
    {
        if (array_key_exists($name, $this->__data)) {
            $this->__data[$name] = null;
            $this->addUpdatedField($name);
        }
    }

    public function __set($name, $value)
    {
        $this->offsetSet($name, $value);
    }

    public function __get($name)
    {
        return $this->offsetGet($name);
    }

    public function __unset($name)
    {
        $this->offsetUnset($name);
    }

    public function __isset($name): bool
    {
        return $this->offsetExists($name);
    }

    public function current()
    {
        return $this->__data[$this->keys[$this->currentPosition]];
    }

    public function next()
    {
        ++$this->currentPosition;
    }

    public function key()
    {
        return $this->keys[$this->currentPosition];
    }

    public function valid(): bool
    {
        return array_key_exists($this->currentPosition, $this->keys);
    }

    public function rewind()
    {
        $this->keys = array_keys($this->__data);
        $this->currentPosition = 0;
    }
}
