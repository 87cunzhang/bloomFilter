<?php
namespace BloomFilter;

/**
 * 布隆过滤器
 * Class BloomFilter
 * @author 靳文垒
 */
class BloomFilter
{
    private $redis;
    private $bitArraySize;
    private $hashFunctions;

    public function __construct($redis)
    {
        $this->redis = $redis;
        // 位数组大小
        $this->bitArraySize = 1 << 20;
        // hash函数
        $this->hashFunctions = [
            function ($element) {
                return crc32($element);
            },
            function ($element) {
                return hexdec(substr(md5($element), 0, 8));
            }
        ];
    }


    /**
     * 添加元素
     * @param $element
     */
    public function add($element)
    {
        foreach ($this->hashFunctions as $hashFunction) {
            $bit = $hashFunction($element) % $this->bitArraySize;
            $this->redis->setbit('bloom_filter', $bit, 1);
            echo "元素$element 的位置是: $bit\n";
        }
    }


    /**
     * 判断元素是否存在
     * @param $element
     * @return bool
     */
    public function exists($element): bool
    {
        foreach ($this->hashFunctions as $hashFunction) {
            $bit = $hashFunction($element) % $this->bitArraySize;

            if (!$this->redis->getbit('bloom_filter', $bit)) {
                return false;
            }
        }

        return true;
    }

}