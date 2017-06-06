# redis-model

## 安装
~~~
composer require limingxinleo/redis-model 
~~~

## 使用方法
BaseModel.php
~~~
<?php

namespace App\Models\RedisModel;

use limx\utils\RedisModel\Model;

class BaseModel extends Model
{
    protected function initRedisClient($parameters, $options)
    {
        if (!isset($parameters['host'])) {
            $parameters['host'] = env('REDIS_HOST', 'localhost');
        }

        if (!isset($parameters['port'])) {
            $parameters['port'] = env('REDIS_PORT', 6379);
        }

        if (!isset($parameters['auth'])) {
            $parameters['password'] = env('REDIS_AUTH', null);
        }

        if (!isset($parameters['database'])) {
            $parameters['database'] = env('REDIS_INDEX', 0);
        }

        parent::initRedisClient($parameters, $options);
    }
}
~~~

User.php
~~~
<?php

namespace App\Models\RedisModel;

class User extends BaseModel
{
    protected $key = 'redisdmodel:user:{id}';

    protected $type = 'hash';

    protected $fillable = ['id', 'username', 'name'];

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

        return $this->whereIn('id', $primaryKey)->delete();
    }

    public function flushAll()
    {
        return $this->delete();
    }

}
~~~