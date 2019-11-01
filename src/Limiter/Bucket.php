<?php

namespace Janfish\Frequency\Limiter;

/**
 * Bucket
 * Description
 * Author:Robert
 *
 * Class Frequency
 */
class Bucket
{

    /**
     * Author:Robert
     *
     * @var int
     */
    public $limit = 10;

    /**
     * Author:Robert
     *
     * @var int
     */
    public $interval = 10;


    /**
     * Author:Robert
     *
     * @var
     */
    protected $name;

    /**
     * Author:Robert
     *
     * @var
     */
    protected $intervalIncrement;


    /**
     *
     * Author:Robert
     *
     * @var \Predis\Client
     */
    private static $redis;

    /**
     * Author:Robert
     *
     * @var array
     */
    private $_redisConfig;

    /**
     * Author:Robert
     *
     * @var \Predis\Client
     */
    private $_redisInstance;


    /**
     * Author:Robert
     *
     * @param array $config
     * @return \Predis\Client
     */
    public static function redisInstance(array $config = array())
    {
        if (!self::$redis) {
            self::$redis = new \Predis\Client($config);
            self::$redis->getProfile()->defineCommand('limiter', 'Janfish\Frequency\Limiter\Script\Limiter');
        }
        return self::$redis;
    }


    /**
     * Bucket constructor.
     * @param array $options
     * @param array $redisConfig
     * @throws \Exception
     */
    public function __construct(array $options, array $redisConfig)
    {
        if (isset($options['name'])) {
            $this->name = $options['name'];
        }
        if (!isset($options['limit'])) {
            throw  new \Exception('please set bucket limit');
        }
        if (!isset($options['interval'])) {
            throw  new \Exception('please set interval time');
        }
        $this->interval = intval($options['interval']) * 1000;
        $this->limit = intval($options['limit']);
        if (!$this->limit) {
            throw  new \Exception('Set a wrong value for limit param');
        }
        $this->_redisConfig = $redisConfig;
        $this->intervalIncrement = $this->getIntervalIncrement();
        $this->_redisInstance = self::redisInstance($this->_redisConfig);
    }


    /**
     * Author:Robert
     *
     * @return float
     */
    public function getIntervalIncrement()
    {
        return $this->limit / $this->interval;
    }

    /**
     * Author:Robert
     *
     * @return string
     */
    private function getBucketKey()
    {
        return 'J.bucket:'.md5($this->name);
    }

    /**
     * Author:Robert
     *
     * @return bool
     */
    public function check()
    {
        $result = $this->_redisInstance->limiter($this->getBucketKey(), $this->interval, $this->limit);
        return $result == 1;
    }

}