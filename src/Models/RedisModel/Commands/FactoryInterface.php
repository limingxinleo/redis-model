<?php

namespace limx\Models\RedisModel\Commands;

interface FactoryInterface
{
    /**
     * @param string $command redis command in lower case
     * @param array $keys KEYS for redis "eval" command
     * @param array $args ARGV for redis "eval" command
     * @return Command
     */
    public function getCommand($command, $keys = [], $args = []);
}