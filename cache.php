<?php
class Cache{
    var $cache;

    function get($key, $func, $time = 0)
    {
        if(!$this->cache->get($key))
        {
            $val = call_user_func($func);
            $this->cache->set($key, $val, $time);
            return $val;
        } else {
            return $this->cache->get($key);
        }
    }

    function __construct()
    {
        $this->cache = new Memcached();
        $this->cache->addServer('localhost', 11211);

    }
}
