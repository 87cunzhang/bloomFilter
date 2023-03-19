<?php
/**
 * Created by 村长
 * Date: 2023/3/19
 * Time: 11:39
 */

namespace bloomFilter;

class BloomFilter
{
    private $redis;
    private $hashFunctions;
    private $bitArraySize;

    public function __construct($redis, $hashFunctions, $bitArraySize)
    {
        $this->redis         = $redis;
        $this->hashFunctions = $hashFunctions;
        $this->bitArraySize  = $bitArraySize;
    }

    public function add($element)
    {
        foreach ($this->hashFunctions as $hashFunction) {
            $bit = $hashFunction($element) % $this->bitArraySize;
            echo "元素$element 的位置是: $bit\n";
            $this->redis->setbit('bloom_filter', $bit, 1);
        }
    }

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