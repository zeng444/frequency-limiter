<?php

use Janfish\Frequency\Limiter as FrequencyLimiter;

include_once '../vendor/autoload.php';
$redisConfig = [
    'scheme' => 'tcp',
    'host' => 'redis',
    'port' => 6379,
    'database' => 5,
];
$frequencyLimiter = new FrequencyLimiter($redisConfig);
$result = $frequencyLimiter->setRules([
    ['interval' => 10, 'limit' => 3, 'name' => 'clock0:user:752'],
    //    ['interval' => 30, 'limit' => 200, 'name' => 'clock1:user:752'],
])->check();
$message = time().($result ? 'YES'.PHP_EOL : ''.PHP_EOL);
echo $message;
error_log($message, 3, 'access.log');