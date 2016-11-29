<?php

namespace Sensor\Common;

use \Sensor\SensorAbstract;

/**
 * Обычный текстовый вывод
 *
 * @category    Sensor
 * @package     Sensor\Common
 * @author      Evgeniy Vasilev <e.vasilev@office.ngs.ru>
 */
class Text extends SensorAbstract
{
    public function result()
    {
        return isset($this->config['text']) ? $this->config['text'] : '';
    }
}
