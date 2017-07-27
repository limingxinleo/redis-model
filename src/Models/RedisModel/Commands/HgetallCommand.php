<?php

namespace limx\Models\RedisModel\Commands;

class HgetallCommand extends Command
{
    public function getScript()
    {
        $script = <<<LUA
    local values = {}; 
    for i,v in ipairs(KEYS) do 
        values[#values+1] = redis.pcall('hgetall',v); 
    end 
    return {KEYS,values};
LUA;
        return $script;
    }
}