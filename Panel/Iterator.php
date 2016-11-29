<?php

namespace Panel;

use \Sensor\CacheableInterface;

/**
 * Обработка всех датчиков
 *
 * @category    Panel
 * @package     Panel
 * @author      Evgeniy Vasilev <e.vasilev@office.ngs.ru>
 */
class Iterator
{
    /**
     * @var \Sensor\SensorAbstract[]
     */
    protected $instances = [];


    /**
     * @var \Sensor\Cache
     */
    protected $cache = false;


    /**
     * Начало времени пустого ответа
     *
     * Warmup starting timestamp
     *
     * @var null|int
     */
    protected $_loading;

    /**
     * Установка объекта кэша
     *
     * Sets cache instance
     *
     * @param \Sensor\Cache $cache
     */
    public function setCache(\Sensor\Cache $cache)
    {
        $this->cache = $cache;

        return $this;
    }


    /**
     * Добавляет датчик в обработку
     *
     * Adds sensor to handler
     *
     * @param \Sensor\SensorAbstract $object
     */
    public function add(\Sensor\SensorAbstract $object)
    {
        $object->cacheKey .= '.' . count($this->instances);
        $this->instances[] = $object;

        return $this;
    }


    /**
     * Выдает готовый массив данных для строки
     * Данные собирает по всем датчикам
     *
     * Preparing sensor data for output
     * Iterating all sensors
     *
     * @return array
     */
    public function result()
    {
        $result = [];

        foreach($this->instances as $object)
        {
            if ($object instanceof CacheableInterface && $this->cache) {
                $callable = [$object, 'result'];

                if (is_callable($object)) {
                    $callable = $object;
                }

                $value = $this->cache->get($object->cacheKey, $callable, $object->cacheTime);
            } else {
                $value = $object->result();
            }

            if(empty($value)) {
                continue;
            }

            $value = ' ' . $value . ' ';
            $item = ['full_text' => $value];
            if ($color = $object->getColor()) {
                $item['color'] = $color;
            }

            $result[] = $item;
        }

        if (empty($result)) {
            if (!$this->_loading) {
                $this->_loading = time();
            }

            $result[] = ['full_text' => 'loading... ' . \Helper\Time::format(time() - $this->_loading)];
        } else {
            $this->_loading = null;
        }

        return $result;
    }
}
