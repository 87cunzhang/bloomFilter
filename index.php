<?php

namespace bloomFilter;
require_once 'RedisSingleTon.php';
require_once 'BloomFilter.php';

class Test
{
    private $redis;

    public function __construct()
    {
        $this->redis = RedisSingleton::getInstance()->getRedis();
    }

    function test()
    {
        $hashFunctions = [
            function ($element) {
                return crc32($element);
            },
            function ($element) {
                return hexdec(substr(md5($element), 0, 8));
            }
        ];

        $bitArraySize = 1 << 20;

        // 创建布隆过滤器
        $bloomFilter = new BloomFilter($this->redis, $hashFunctions, $bitArraySize);
        $key         = 'world';
        //$bloomFilter->add($key);
        if ($bloomFilter->exists($key)) {
            echo "$key 存在\n";
        } else {
            echo "$key 不存在\n";
        }
    }
}

$test = new Test();
$test->test();




