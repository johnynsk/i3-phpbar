<?php

/**
 * Абстрактный класс источника данных
 *
 * @category    Sensor
 * @package     Sensor
 * @author      Evgeniy Vasilev <e.vasilev@office.ngs.ru>
 */
abstract class Sensor_Abstract{
    /**
     * Ключ кэша
     * @var
     */
    public $cacheKey;


    /**
     * Время кэширования ответа источника
     *
     * @var int
     */
    public $cacheTime = 0;


    /**
     * Цвет в HEX
     * например, #0033ff
     * @var string
     */
    protected $color;


    /**
     * @var null|Sensor_Cache
     */
    protected $cache;


    /**
     * @var Sensor_Di
     */
    protected $di;


    /**
     * Результат сенсора
     * @return string
     */
    abstract public function result();


    /**
     * @param array $config
     */
    public function __construct($config = null)
    {
        $this->config = $config;
        $this->cacheKey = 'i3-phpbar.' . get_called_class();

        if (isset($config['cache_time'])) {
            $this->cacheTime = $config['cache_time'];
        }

        if (isset($config['color'])) {
            $this->color = $config['color'];
        }
    }


    /**
     * Возвращает цвет подсветки сенсора, HEX
     *
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }


    /**
     * Установка Di
     *
     * @param Sensor_Di $di
     * @return $this
     */
    public function setDi(Sensor_Di $di)
    {
        $this->di = $di;

        return $this;
    }
}
