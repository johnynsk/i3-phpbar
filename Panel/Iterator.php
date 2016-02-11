<?php

/**
 * Обработка всех датчиков
 *
 * @category    Panel
 * @package     Panel
 * @author      Evgeniy Vasilev <e.vasilev@office.ngs.ru>
 */
class Panel_Iterator
{
    /**
     * @var Sensor_Abstract[]
     */
    protected $instances = [];


    /**
     * @var Sensor_Cache
     */
    protected $cache = false;


    /**
     * Начало времени пустого ответа
     *
     * @var null|int
     */
    protected $_loading;

    /**
     * Установка объекта кэша
     * @param Sensor_Cache $cache
     */
    public function setCache($cache)
    {
        $this->cache = $cache;
    }


    /**
     * Добавляет датчик в обработку
     *
     * @param Sensor_Abstract $object
     */
    public function add($object)
    {
        $object->cacheKey .= '.' . count($this->instances);
        $this->instances[] = $object;
    }


    /**
     * Выдает готовый массив данных для строки
     * Данные собирает по всем датчикам
     *
     * @return array
     */
    public function result()
    {
        $result = [];

        foreach($this->instances as $object)
        {
            if (!empty($object->cacheKey) && $this->cache) {
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

            $result[] = ['full_text' => 'loading... ' . Helper_Time::format(time() - $this->_loading)];
        } else {
            $this->_loading = null;
        }

        return $result;
    }
}
