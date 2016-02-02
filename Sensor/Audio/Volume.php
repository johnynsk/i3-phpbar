<?php

/**
 * Данные громкости alsa
 *
 * @category    Sensor
 * @package     Sensor_Audio
 * @author      Evgeniy Vasilev <e.vasilev@office.ngs.ru>
 */
class Sensor_Audio_Volume extends Sensor_Abstract
{
    /**
     * @param array|null $config
     */
    public function __construct($config)
    {
        parent::__construct($config);

        $this->cacheTime = 5;
    }


    /**
     * Возвращает данные о громкости
     *
     * @return string
     */
    public function result() {
        $result = exec('amixer -c 0 sget Master | grep -o "Mono.*Playback.*" | grep -o "[0-9]*%"');
        $result = '♪' . $result;
        return $result;
    }
}
