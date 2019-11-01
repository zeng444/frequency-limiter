<?php

namespace Janfish\Frequency\Limiter\Script;

/**
 * LUA SCRIPT FOR REDIS
 * Author:Robert
 *
 * Class Limiter
 * @package Janfish\Frequency\Script
 */
class Limiter extends \Predis\Command\ScriptCommand
{
    /**
     * Author:Robert
     *
     * @return int
     */
    public function getKeysCount()
    {
        return 3;
    }

    /**
     * Token bucket check
     * Author:Robert
     *
     * @return string
     */
    public function getScript()
    {
        return <<<LUA
            local bucketName = KEYS[1]
            local interval = tonumber(KEYS[2])
            local expire = (interval / 1000) + 3
            local limit = tonumber(KEYS[3])
            local bucket = redis.call('hgetall',bucketName)
            if (bucket[1]) then
              local intervalIncrement = limit / interval
              local createAt = tonumber(bucket[2])
              local remain = tonumber(bucket[4])
              local redisTime = redis.call('time')
              local currentTime =  redisTime[1]*1000 + (math.floor(redisTime[2]/1000))
              local intervalSinceCreatedAt =  currentTime - createAt
              local currentRemain
              if ( intervalSinceCreatedAt > interval ) then
                  currentRemain = limit
              else
                 currentRemain = (intervalSinceCreatedAt * intervalIncrement) + remain
                 if (  currentRemain > limit ) then
                     currentRemain = limit
                 end
              end
              if ( currentRemain < 1 ) then
                redis.call('hmset',bucketName,'createAt',currentTime,'remain',currentRemain)
                redis.call('expire',bucketName,expire)
                return false
              else
                redis.call('hmset',bucketName,'createAt',currentTime,'remain',currentRemain-1)
                redis.call('expire',bucketName,expire)
              return true
              end
            else
              local redisTime = redis.call('time')
              local currentTime =  redisTime[1]*1000 + (math.floor(redisTime[2]/1000))
              redis.call('hmset',bucketName,'createAt',currentTime,'remain',limit-1)
              redis.call('expire',bucketName,expire)
              return true
            end
LUA;
    }

}