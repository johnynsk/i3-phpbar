<?php
class Currenttime extends Cli_Abstract{
    var $color = "";
    protected $_colors = [
        'cool' =>  '#78ECEC',
        'green' => '#78EC94',
        'warn' =>  '#FFD83F',
        'alarm' => '#FF3F3F',
        'white' => '#FFFFFF',
        '' => ''
        ];

    public function result()
    {
        $hour = date("H");
        $minute = date("i");
        $second = date("s");
        $color = "";
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
            $color = 'warn';
        } elseif (($hour == 13 && $minute >= 30) || ($hour == 14 && $minute <= 30)) {
            $this->color = 'alarm';
        } elseif ($hour < 10) {
            $color = 'cool';
        } else {
            $color = 'green';
        }
        $this->color = $this->_colors[$color];
        return date("H:i:s");
    }
}
