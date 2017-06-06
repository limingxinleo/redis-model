<?php

namespace limx\utils\RedisModel;

use limx\utils\RedisModel\Exception;

class QueryBuilder
{
    protected $key;

    protected $queryKeys = [];

    protected $whereIns = [];

    protected $whereBetweens = [];

    protected $fieldNeedles = [];

    public function __construct($key)
    {
        $this->key = $key;
    }

    /**
     * @param $field
     * @param $needle
     * @return $this
     */
    public function setFieldNeedle($field, $needle)
    {
        $this->fieldNeedles[$field] = $needle;

        return $this;
    }

    /**
     * Get valid keys have been built
     * @return array
     */
    public function getQueryKeys()
    {
        $this->getValidQueryKeys();

        return $this->queryKeys;
    }

    /**
     * Get first key
     * @return string|null
     */
    public function firstQueryKey()
    {
        $this->getValidQueryKeys();

        return $this->queryKeys ? $this->queryKeys[0] : null;
    }

    /**
     * @param string $bindingKey
     * @param string $value
     * @return QueryBuilder
     */
    public function whereEqual($bindingKey, $value)
    {
        return $this->whereIn($bindingKey, [$value]);
    }

    /**
     * @param $field string
     * @param string[] $values
     * @return $this
     */
    public function whereIn($field, array $values)
    {
        $this->whereIns[$field] = isset($this->whereIns[$field]) ?
            array_merge($this->whereIns[$field], $values) : $values;

        return $this;
    }

    /**
     * @param string $key
     * @param array $range [min,max]
     * @return $this
     * @throws Exception
     */
    public function whereBetween($key, $range)
    {
        if (!is_int($range[0]) || !is_int($range[1])) {
            throw new Exception('whereBetween parameters must be integer');
        }

        if ($range[1] <  $range[0]) {
            throw new Exception('whereBetween up bound must be greater than or equal to low bound');
        }

        $this->whereBetweens[$key] = $range;

        return $this;
    }

    /**
     * @return $this
     */
    public function refresh()
    {
        $this->queryKeys = [];
        $this->whereIns = [];
        $this->whereBetweens = [];

        return $this;
    }

    /**
     * Get valid query keys
     * @return $this
     */
    protected function getValidQueryKeys()
    {
        $whereIns = [];

        foreach ($this->whereBetweens as $field => $range) {
            $whereIns[$field] = range($range[0], $range[1]);
        }

        foreach ($whereIns as $key => $value) {
            $this->whereIn($key, $value);
        }

        $this->queryKeys = [];

        foreach ($this->whereIns as $field => $range) {
            if ($this->queryKeys === []) {
                foreach ($range as $value) {
                    $this->queryKeys[] = $this->bindValue($this->key, $field, $value);
                }
            } else {
                $queryKeys = $this->queryKeys;
                $this->queryKeys = [];
                foreach ($queryKeys as $item) {
                    foreach ($range as $value) {
                        $this->queryKeys[] = $this->bindValue($item, $field, $value);
                    }
                }
            }
        }

        $this->queryKeys = array_unique(array_values($this->queryKeys));

        return $this;
    }

    /**
     * @param string $queryKey
     * @param string $field
     * @param string $value
     * @return string
     */
    protected function bindValue($queryKey, $field, $value)
    {
        return str_replace($this->fieldNeedles[$field], $value, $queryKey);
    }
}