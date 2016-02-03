<?php

/**
 * Парсит и выводит погоду
 *
 * @category    Sensor
 * @package     Sensor_Audio
 * @author      Evgeniy Vasilev <e.vasilev@office.ngs.ru>
 */
class Sensor_Conditions_Weather extends Sensor_Abstract
{
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

        return $conditions['temperature'] . '°C';
    }
}
