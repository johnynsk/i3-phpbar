<?php

namespace Sensor\Conditions;

use \Sensor\SensorAbstract;
use \Sensor\CacheableInterface;

/**
 * Парсит и выводит погоду
 *
 * @category    Sensor
 * @package     Sensor\Conditions
 * @author      Evgeniy Vasilev <e.vasilev@office.ngs.ru>
 */
class Weather extends SensorAbstract implements CacheableInterface
{
    /**
     * Предыдущий замер
     *
     * @var null|int
     */
    protected $_lastTemperature = null;


    /**
     * Тренд
     *
     * @var null|int
     */
    protected $_trend = null;


    /**
     * Забирает температуру
     *
     * @return mixed|string
     */
    public function result()
    {
        $json = $this->di->getCurl()->get('http://pogoda.ngs.ru/api/v1/forecasts/current?city=' . $this->config['city']);
        $data = json_decode($json, true);

        if (!isset($data['forecasts'])) {
            throw new Sensor_Exception('Не удалось получить данные');
        }

        $conditions = reset($data['forecasts']);
        $temperature = $conditions['temperature'];

        $trend = '';
        $this->_determineTrend($temperature);
        if ($this->_trend > 0) {
            $trend = '↑';
        } elseif ($this->_trend < 0) {
            $trend = '↓';
        }

        return $temperature . '°C' . $trend;
    }


    /**
     * Определить тренд температуры
     *
     * @param int $temperature
     */
    protected function _determineTrend($temperature)
    {
        if (is_null($this->_lastTemperature)) {
            $this->_lastTemperature = $temperature;
        } elseif ($this->_lastTemperature > $temperature) {
            $this->_lastTemperature = $temperature;
            $this->_trend = -1;
        } elseif ($this->_lastTemperature < $temperature) {
            $this->_lastTemperature = $temperature;
            $this->_trend = 1;
        }

        return $this->_trend;
    }
}
