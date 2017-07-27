# redis-model

## 安装
~~~
composer require limingxinleo/redis-model 
~~~

## 使用方法
BaseModel.php
~~~
<?php

namespace limx\Tests\App\RedisModel;

use limx\Models\RedisModel\Model;

class BaseModel extends Model
{
    /**
     * @desc   初始化Redis客户端
     * @author limx
     * @param $parameters
     * @param $options
     */
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

    /**
     * @desc   获取Redis模型的Key值
     * @author limx
     * @param $id
     * @return mixed
     */
    public function getPrimaryKey($id)
    {
        return parent::getPrimaryKey($id);
    }

    /**
     * @desc   覆盖更新
     * @author limx
     * @param $primaryKey
     * @param $data
     * @return bool
     */
    public function replace($primaryKey, $data, $ttl = null)
    {
        $info = array_intersect_key($data, array_flip((array)$this->fillable));
        $data = array_merge(array_fill_keys($this->fillable, ''), $info);
        return $this->create($primaryKey, $data, $ttl);
    }

    /**
     * @desc   删除
     * @author limx
     * @param string $primaryKey
     * @return bool|int
     */
    public function destroy($primaryKey)
    {
        if (!is_array($primaryKey)) {
            $primaryKey = [$primaryKey];
        }

        return $this->whereIn($this->primaryFieldName, $primaryKey)->delete();
    }

    /**
     * @desc   删除所有
     * @author limx
     * @return bool|int
     */
    public function flushAll()
    {
        return $this->delete();
    }
}
~~~

User.php
~~~
namespace limx\Tests\App\RedisModel;

class User extends BaseModel
{
    protected $key = 'redisdmodel:user:{id}';

    protected $type = 'hash';

    protected $fillable = ['id', 'username', 'name'];

}
~~~