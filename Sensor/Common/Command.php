<?php

/**
 * Исполнение комманды
 *
 * @category    Sensor
 * @package     Sensor_Common
 * @author      Evgeniy Vasilev <e.vasilev@office.ngs.ru>
 */
class Sensor_Common_Command extends Sensor_Abstract
{
    public function result()
    {
        return exec($this->config['command']);
    }
}
