<?php

namespace Sensor;

/**
 * Обёртка над memcached
 */
class Cache
{
    /**
     * @var Memcached
     */
    protected $cache;


    /**
     * конструктор
     */
    public function __construct()
    {
        $this->cache = new \Memcached();
        $this->cache->addServer('localhost', 11211);

    }

    /**
     * Пытается достать значение из кэша, в противном случае - вызывает функцию и возвращенное значение кидает в кэш
     *
     * @param string $key
     * @param callable|array $func
     * @param int $time
     *
     * @return mixed
     */
    public function get($key, $func, $time = 0)
    {
        if(!$this->cache->get($key))
        {
            $val = call_user_func($func);
            if ($time > 0) {
                $this->cache->set($key, $val, $time);
            }
            return $val;
        } else {
            return $this->cache->get($key);
        }
    }
}
