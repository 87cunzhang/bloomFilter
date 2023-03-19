<?php
/**
 * Created by 村长
 * Date: 2023/3/19
 * Time: 17:12
 */
namespace bloomFilter;
use Redis;

class RedisSingleTon {
    private static $instance;
    private $redis;
    private function __construct() {
        try {
            $this->redis = new Redis();
            $this->redis->connect('47.92.232.152', 6379);
            $this->redis->auth('3.14@.com');
        }catch (\Exception $e) {
            echo $e->getMessage();
        }

    }

    public static function getInstance(): RedisSingleTon
    {
        if (!self::$instance) {
            self::$instance = new RedisSingleton();
        }
        return self::$instance;
    }

    public function getRedis(): Redis
    {
        return $this->redis;
    }
}



