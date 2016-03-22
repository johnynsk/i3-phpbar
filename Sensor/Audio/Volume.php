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
        $level = (int)exec('amixer -c 0 sget Master | grep -o "Mono.*Playback.*" | grep -o "[0-9]*%"');

        if ($level < 50) {
            $this->color = null;
        } elseif ($level <= 65) {
            $this->color = '#66ff66';
        } elseif ($level < 80) {
            $this->color = '#ffff66';
        } else {
            $this->color = '#ff6666';
        }

        $result = '♪' . $level . '%';
        return $result;
    }
}
