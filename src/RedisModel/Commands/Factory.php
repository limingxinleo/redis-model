<?php

namespace limx\utils\RedisModel\Commands;

class Factory implements FactoryInterface
{
    /**
     * @param string $command redis command in lower case
     * @param array $keys KEYS for redis "eval" command
     * @param array $args ARGV for redis "eval" command
     * @return Command
     * @throws \Exception
     */
    public function getCommand($command, $keys = [], $args = [])
    {
        $instance = null;

        $className = __NAMESPACE__ . '\\' . ucfirst($command) . 'Command';

        if (class_exists($className)) {
            $instance = new $className($keys, $args);

            if (! $instance instanceof Command) {
                throw new \Exception("$className is not subclass of " . __NAMESPACE__ . '\\Command');
            }
        } else {
            throw new \Exception("$className not exists");
        }

        return $instance;
    }
}