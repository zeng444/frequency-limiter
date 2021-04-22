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
$rules = [
    ['interval' => 5, 'limit' => 1, 'name' => 'cd1:user:752','msg'=>'单位时间请求5秒内不能大于1次'],
    ['interval' => 10, 'limit' => 3, 'name' => 'cd2:user:752','msg'=>'单位时间请求10秒内不能大于3次'],

];
$result = $frequencyLimiter->setRules($rules)->check();
if (!$result) {
    echo $rules[$frequencyLimiter->getCurrentRuleIndex()]['msg'] . PHP_EOL;
    die();
}
echo 'OK' . PHP_EOL;