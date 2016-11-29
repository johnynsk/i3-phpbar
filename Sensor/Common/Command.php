<?php

namespace Sensor\Common;

use \Sensor\SensorAbstract;
use \Sensor\CacheableInterface;

/**
 * Исполнение комманды
 *
 * @category    Sensor
 * @package     Sensor\Common
 * @author      Evgeniy Vasilev <e.vasilev@office.ngs.ru>
 */
class Command extends SensorAbstract implements CacheableInterface
{
    public function result()
    {
        return exec($this->config['command']);
    }
}
