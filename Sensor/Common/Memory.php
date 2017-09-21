<?php

namespace Sensor\Common;

use \Sensor\SensorAbstract;

/**
 * Показывает текущее время, подкрашивает обед/переработку
 *
 * @category    Sensor
 * @package     Sensor\Common
 * @author      Evgeniy Vasilev <e.vasilev@office.ngs.ru>
 */
class Memory extends SensorAbstract
{
    /**
     * Цветовые схемы
     *
     * @var array
     */
    protected $colors = [
        'cool' =>  '78ECEC',
        'green' => '78EC94',
        'warn' =>  'FFD83F',
        'alarm' => 'FF3F3F',
        'white' => 'FFFFFF',
        '' => ''
        ];

    protected $scheme = [
        0 => '78ECEC',
        100 => '78ECEC'
    ];


    /**
     * Возвращает время - подкрашивает для привлечения внимания
     *
     * @return bool|string
     */
    public function result()
    {
        /*
         * user@debian:~/.i3$ cat /proc/meminfo | grep MemTotal | awk '{print $2}'
         * 6041652
         * user@debian:~/.i3$ cat /proc/meminfo | grep MemAvailable | awk '{print $2}'
         * 5201060
         *
         */
        $total = exec("cat /proc/meminfo | grep MemTotal | awk '{print \$2}'");
        $available = exec("cat /proc/meminfo | grep MemAvailable | awk '{print \$2}'");

        $mTotal = round($total / 1024);
        $mAvailable = round($available / 1024);

        $percent = round($available/$total, 2) * 100;

        if ($percent < 10) {
            $color = $this->colors['alarm'];
        } elseif ($percent < 30) {
            $color = $this->colors['warn'];
        } elseif ($percent < 50) {
            $color = $this->colors['white'];
        } elseif ($percent < 70) {
            $color = $this->colors['green'];
        } else {
            $color = $this->colors['cool'];
        }

        $this->color = '#' . $color;

        return "$mAvailable/{$mTotal}M ($percent%)";
    }
}
