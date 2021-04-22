<?php

namespace Janfish\Frequency;

use Janfish\Frequency\Limiter\Bucket;

/**
 * Author:Robert
 *
 * Class Limiter
 * @package Janfish\Frequency
 */
class Limiter
{

    /**
     * Author:Robert
     *
     * @var array
     */
    private $_rules = [];

    /**
     * @var array
     */
    private $_currentRuleIndex = null;


    /**
     * Author:Robert
     *
     * @var
     */
    private static $redis;

    /**
     * Author:Robert
     *
     * @var array
     */
    private $_config;

    /**
     * Limiter constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->_config = $config;
    }

    /**
     * Author:Robert
     *
     * @param array $config
     * @return \Predis\Client
     */
    public static function redisInstance(array $config = [])
    {
        if (!self::$redis) {
            self::$redis = new \Predis\Client($config);
            self::$redis->getProfile()->defineCommand('limiter', 'Janfish\Frequency\Script\Limiter');
        }
        return self::$redis;
    }

    /**
     * Author:Robert
     *
     * @param array $rules
     * @return $this
     */
    public function setRules(array $rules)
    {
        $this->_rules = $rules;
        return $this;
    }

    /**
     * Author:Robert
     *
     * @param $rules
     * @return bool
     * @throws \Exception
     */
    public function check(array $rules = [])
    {
        if ($rules) {
            $this->_rules = $rules;
        }
        foreach ($this->_rules as $index => $rule) {
            $bucket = new Bucket($rule, $this->_config);
            if (!$bucket->check() && $this->_currentRuleIndex === null) {
                $this->_currentRuleIndex = $index;
            }
        }
        return $this->_currentRuleIndex === null;
    }

    /**
     * @return array
     */
    public function getCurrentRuleIndex()
    {
        return $this->_currentRuleIndex;
    }

}