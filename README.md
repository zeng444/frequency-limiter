# Frequency limiter

> Frequency limiter base on token bucket algorithm

## Main features

- implement this feature by lua script 
- shared locks are implemented when concurrent requests occur

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
    ['interval' => 10, 'limit' => 51, 'name' => 'clock0:userId:752'],
    ['interval' => 30, 'limit' => 200, 'name' => 'clock1:userId:752'],
]);
if (!$frequencyLimiter->check()) {
    die('Get out my way!');
}
```

##  Rules configuration

| key     |  type    |  desc  |
|---------|----------|-----------|
| interval | int      | 间隔时间，单位秒    |
| limit    | int      | 添加到桶里的token数 |
| name     | string    | 筒的唯一标识名        |


## Reference

- https://en.wikipedia.org/wiki/Token_bucket
- https://baike.baidu.com/item/token%20bucket/4315253?fr=aladdin