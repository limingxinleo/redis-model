<?php

namespace limx\utils\RedisModel\Commands;


class GetCommand extends Command
{
    public function getScript()
    {
        $script = <<<LUA
    local values = {}; 
    for i,v in ipairs(KEYS) do 
        values[#values+1] = redis.pcall('get',v); 
    end 
    return {KEYS,values};
LUA;
        return $script;
    }
}