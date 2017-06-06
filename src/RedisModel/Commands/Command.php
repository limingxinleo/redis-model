<?php

namespace limx\utils\RedisModel\Commands;

use limx\utils\RedisModel\Exception;
use Predis\Command\ScriptCommand;

abstract class Command extends ScriptCommand
{
    /**
     * Keys to manipulate
     * @var array
     */
    protected $keys;

    /**
     * Additional arguments
     * @var array
     */
    protected $arguments;

    /**
     * Lua script
     * @var string
     */
    protected $script;

    /**
     * Keys ttl in second
     * @var
     */
    protected $ttl;

    /**
     * Command constructor.
     * @param array $keys
     * @param array $args
     */
    public function __construct($keys = [], $args = [])
    {
        $this->keys = $keys;
        $this->arguments = $args;
    }

    public function getArguments()
    {
        return array_merge($this->keys, $this->arguments);
    }

    public function getKeysCount()
    {
        return count($this->keys);
    }

    /**
     * Set keys ttl
     * @param int $seconds
     * @return $this
     */
    public function setTtl($seconds)
    {
        $this->ttl = $seconds;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTtl()
    {
        return $this->ttl;
    }

    /**
     * Resolve data returned from "eval"
     *
     * @param $data
     * @return mixed
     * @throws Exception
     */
    function parseResponse($data)
    {
        if (isset($data[0]) && count($data[0]) === $this->getKeysCount()) {
            $items = array_combine($data[0], $data[1]);

            return array_filter($items, [$this, 'notNil']);
        }

        throw new Exception('Error when evaluate lua script. Response is: ' . json_encode($data));
    }

    /**
     * @param $item
     * @return bool
     */
    protected function notNil($item)
    {
        return $item !== [] && $item !== null;
    }

    /**
     * @return string
     */
    protected function joinArguments()
    {
        $joined = '';

        for ($i = 1; $i <= count($this->arguments); $i++) {
            $joined .= "ARGV[$i],";
        }

        return rtrim($joined, ',');
    }

    protected function getTmpKey()
    {
        return uniqid('__limen__redmodel__' . time() . '__');
    }

    protected function luaSetTtl($ttl)
    {
        if (!$ttl) {
            return '';
        }

        return <<<LUA
redis.pcall('expire', v, $ttl);
LUA;
    }
}