<?php

namespace limx\Models\RedisModel\Commands;

class HmsetCommand extends Command
{
    public function getScript()
    {
        $argString = $this->joinArguments();

        $luaSetTtl = $this->luaSetTtl($this->getTtl());

        $setTtl = $luaSetTtl ? 1 : 0;

        $script = <<<LUA
    local values = {}; 
    local setTtl = '$setTtl';
    for i,v in ipairs(KEYS) do 
        values[#values+1] = redis.pcall('hmset',v, $argString); 
        if setTtl == '1' then
            $luaSetTtl
        end
    end
    return {KEYS,values};
LUA;
        return $script;
    }

}