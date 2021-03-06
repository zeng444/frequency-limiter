# Frequency limiter

> Frequency limiter base on token bucket algorithm

## Main features

- Implement this feature by lua script 
- Shared locks are implemented when concurrent requests occur

## How to use

```php
$redisConfig = [
    'scheme' => 'tcp',
    'host' => 'redis',
    'port' => 6379,
    'database' => 5,
];
$frequencyLimiter = new FrequencyLimiter($redisConfig);
$result = $frequencyLimiter->setRules([
    ['interval' => 10, 'limit' => 5, 'name' => 'clock0:userId:752'],
    ['interval' => 35, 'limit' => 15, 'name' => 'clock1:userId:752'],
    ['interval' => 80, 'limit' => 30, 'name' => 'clock2:userId:752'],
]);
if (!$frequencyLimiter->check()) {
    die($frequencyLimiter->getCurrentRuleIndex());
}

```

```php
$frequencyLimiter = new FrequencyLimiter($redisConfig);
$result = $frequencyLimiter->setRules([
    ['interval' => 10, 'limit' => 5, 'name' => $userName.':userId:752'],
]);
if (!$frequencyLimiter->check()) {
    die('Maximum number of errors reached');
}
if($userName === 'accountName' && $password==='abc'){
    $frequencyLimiter->reset();
}
echo "Login successful"
```

##  Rules configuration

| key     |  type    |  desc  |
|---------|----------|-----------|
| interval | int      | Interval time in seconds    |
| limit    | int      | Token quantity |
| name     | string    | Name of bucket       |


## Reference

- https://en.wikipedia.org/wiki/Token_bucket
- https://baike.baidu.com/item/token%20bucket/4315253?fr=aladdin