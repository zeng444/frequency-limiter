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
    die('Get out of my way!');
}
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