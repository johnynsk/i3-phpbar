<?php

/**
 * Данные о воспроизводимом треке
 * Moc Player
 *
 * @category    Sensor
 * @package     Sensor_Audio
 * @author      Evgeniy Vasilev <e.vasilev@office.ngs.ru>
 */
final class Sensor_Audio_Mocp extends Sensor_Abstract
{
    /**
     * Предыдущие данные
     *
     * @var string
     */
    private $title;


    /**
     * Счетчик цикла с момента получения измененного значения
     * @var int
     */
    private $tick = 0;


    /**
     * Цвет
     * @var int[]
     */
    private $colorComponent = [
        'R' => 255,
        'G' => 255,
        'B' => 255
    ];


    /**
     * Шаг изменения цвета
     *
     * @var int
     */
    private $step = 8;


    /**
     * @return string
     */
    public function result()
    {
        $title = '';
        $exec = exec('mocp -i 2> /dev/null | grep -o "^Title: .*"');
        if (!preg_match('#^Title: (.*)#usi', $exec, $m)) {
            return;
        }

        $title = $m[1];


        if ($this->title != $title) {
            var_dump(1);
            $this->title = $title;
            $this->tick = 0;
            $this->colorComponent['G'] = 187;
            $this->colorComponent['B'] = 0;
        }

        $this->fadeOut();

        return $this->title;
    }


    /**
     * Восстанавливает цвет к исходному
     */
    protected function fadeOut()
    {
        foreach ($this->colorComponent as $component => &$value) {
            if ($value + $this->step < 255) {
                $value += $this->step;
            } else {
                $value = 255;
            }
        }

        $this->tick++;
        $this->color = '#' . dechex($this->colorComponent['R']) . dechex($this->colorComponent['G']) . dechex($this->colorComponent['B']);
    }
}
