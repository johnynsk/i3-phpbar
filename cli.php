<?php
class Cli{
    var $spaces = 6;
    var $objects = [];
    var $cache = false;

    function setCache($cache)
    {
        $this->cache = $cache;
    }

    function add($object)
    {
        $this->objects[] = $object;
    }

    function result()
    {
        $result = [];
        foreach($this->objects as $object)
        {
            if (!empty($object->cacheKey) && $this->cache) {
                $value = $this->cache->get($object->cacheKey, array($object, "result"), $object->cacheTime);
            } else {
                $value = $object->result();
            }

            if(empty($value)) {
                continue;
            }

            $value = " " . $value . " ";
            $item = ["full_text" => $value];
            if (!empty($object->color)) {
                $item["color"] = $object->color;
            }
            $result[] = $item;
        }
        return $result;
    }
}
