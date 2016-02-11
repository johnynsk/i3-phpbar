<?php

/**
 * Набор функций по работе со временем
 *
 * Class Helper_Time
 */
class Helper_Time
{
    /**
     * Форматирование времени
     *
     * @param $timeDiff
     * @return string
     */
    static public function format($timeDiff)
    {
        if ($timeDiff < 60) {
            return (int)$timeDiff . 's';
        } elseif ($timeDiff < 3600) {
            return (int)($timeDiff / 60) . 'm ' . (int)($timeDiff % 60) . 's';
        } elseif ($timeDiff < 86400) {
            return (int)($timeDiff / 3600) . 'h ' . (int)($timeDiff % 3600 / 60) . 'm';
        }

        return (int)($timeDiff / 86400) . 'd ' . (int)($timeDiff % 86400 / 3600) . 'h' . (int)($timeDiff % 3600 / 60) . 'm';
    }
}