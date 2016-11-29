<?php

namespace Sensor\Additional;

use \Sensor\SensorAbstract;
use \Sensor\CacheableInterface;

/**
 * Класс для работы с RSS источниками
 *
 * @category    Sensor
 * @package     Sensor\Additional
 * @author      Evgeniy Vasilev <e.vasilev@office.ngs.ru>
 */
class Rss extends Sensor_Abstract implements CacheableInterface
{
    /**
     * Паттерн для парсера
     *
     * @var string
     */
    protected $pattern = '#<item>\\s+<title>(?:<!\[CDATA\[|)(.*?)(?:|\]\]>)<\/title#usi';


    /**
     * Текущая новость
     *
     * @var int
     */
    protected $itemOffset = 0;


    /**
     * Конструктор
     *
     * @param array $config
     */
    public function __construct($config)
    {
        parent::__construct($config);

        $this->config['cache_time_news'] = !empty($config['cache_time_news']) ? $config['cache_time_news'] : 600;
    }


    /**
     * Получение данных источников
     *
     * @return string
     */
    public function result() {
        $result = '';
        $data = $this->_getData();

        if (count($data) == 0) {
            return '';
        } elseif ($this->itemOffset == count($data)) {
            //циклимся по кругу
            $this->itemOffset = 0;
        }

        return $data[$this->itemOffset++];
    }


    /**
     * Получение новостей, обновление кэша
     *
     * @return string[]
     */
    protected function _getData()
    {
        $callback = function () {
            $result = [];
            foreach ($this->config['feeds'] as $feed) {
                $result = array_merge($result, $this->_parseSource($feed));
            }

            shuffle($result);
            return $result;
        };

        return $this->di->getCache()->get($this->cacheKey . '.news', $callback, $this->config['cache_time_news']);
    }


    /**
     * Получает данные источника
     *
     * @param array $feed
     * @return string[]
     */
    protected function _parseSource($feed)
    {
        $url = '';
        $source = '';

        if (is_array($feed) && count($feed) == 2) {
            $source = reset($feed);
            $url = next($feed);
        } elseif (is_string($feed)) {
            $url = $feed;
        }

        $xml = $this->di->getCurl()->get($url);

        preg_match_all($this->pattern, $xml, $matches);
        foreach ($matches[1] as &$item) {
            if(!empty($source)) {
                $item .= ' [' . $source . ']';
            }
        }

        return $matches[1];
    }
}
