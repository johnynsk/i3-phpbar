<?php

/**
 * Показывает текущее время, подкрашивает обед/переработку
 *
 * @category    Sensor
 * @package     Sensor_Common
 * @author      Evgeniy Vasilev <e.vasilev@office.ngs.ru>
 */
class Sensor_Common_CurrentTime extends Sensor_Abstract
{
    /**
     * Цветовые схемы
     *
     * @var array
     */
    protected $colors = [
        'cool' =>  '#78ECEC',
        'green' => '#78EC94',
        'warn' =>  '#FFD83F',
        'alarm' => '#FF3F3F',
        'white' => '#FFFFFF',
        '' => ''
        ];


    /**
     * Возвращает время - подкрашивает для привлечения внимания
     *
     * @return bool|string
     */
    public function result()
    {
        $hour = date('H');
        $minute = date('i');
        $second = date('s');
        $color = '';
        if ($hour == 18 && $minute < 40) {
            $color = 'warn';
        } elseif ($hour ==18) {
            $color = 'alarm';
        } elseif ($hour == 19 && $minute < 50) {
            if($second %2 == 0) {
                $color = 'white';
            } else {
                $color = 'alarm';
            }
        } elseif ($hour == 13 && $minute < 30) {
            $color = 'green';
        } elseif (($hour == 13 && $minute >= 30) || ($hour == 14 && $minute <= 30)) {
            $this->color = 'alarm';
        } elseif ($hour < 10) {
            $color = 'cool';
        } else {
            $color = 'cool';
        }

        $this->color = $this->colors[$color];

        return date('H:i:s');
    }
}
