<?php

namespace limx\Tests\App\RedisModel;

use limx\Models\RedisModel\Model;

class BaseModel extends Model
{
    protected function initRedisClient($parameters, $options)
    {
        if (!isset($parameters['host'])) {
            $parameters['host'] = '127.0.0.1';
        }

        if (!isset($parameters['port'])) {
            $parameters['port'] = 6379;
        }

        if (!isset($parameters['auth'])) {
            $parameters['password'] = '910123';
        }

        if (!isset($parameters['database'])) {
            $parameters['database'] = 0;
        }

        parent::initRedisClient($parameters, $options);
    }

    public function replace($primaryKey, $data)
    {
        $info = array_intersect_key($data, array_flip((array)$this->fillable));
        $data = array_merge(array_fill_keys($this->fillable, ''), $info);
        return $this->create($primaryKey, $data);
    }

    public function destroy($primaryKey)
    {
        if (!is_array($primaryKey)) {
            $primaryKey = [$primaryKey];
        }

        return $this->whereIn($this->primaryFieldName, $primaryKey)->delete();
    }

    public function flushAll()
    {
        return $this->delete();
    }
}