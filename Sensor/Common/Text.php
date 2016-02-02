<?php

/**
 * Обычный текстовый вывод
 *
 * @category    Sensor
 * @package     Sensor_Common
 * @author      Evgeniy Vasilev <e.vasilev@office.ngs.ru>
 */
class Sensor_Common_Text extends Sensor_Abstract
{
    public function result()
    {
        return isset($this->config['text']) ? $this->config['text'] : '';
    }
}
