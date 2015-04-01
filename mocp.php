<?php
class Mocp extends Cli_Abstract{
//    var $cacheKey = "local.bar.mocp";
//    var $cacheTime = 2;

    var $oldTitle = '';
    var $tick = 0;
    var $color = '';
    var $colorR = 255;
    var $colorG = 255;
    var $colorB = 255;
    var $step = 8;

    function result()
    {
        $result = '';
        $exec = exec('mocp -i 2> /dev/null | grep -o "^Title: .*"');
        if (preg_match('#^Title: (.*)#usi', $exec, $m)) {
            $result = $m[1];
        }

        if ($this->oldTitle != $result) {
            $this->tick = 0;
            $this->oldTitle = $result;
            $this->colorG = 187;
            $this->colorB = 0;
        }

        $this->tick++;

        if ($this->colorG < 255 - $this->step) {
            $this->colorG += $this->step;
        }
        if ($this->colorB < 255 - $this->step) {
            $this->colorB += $this->step;
        }

        $this->color = '#' . dechex($this->colorR) . dechex($this->colorG) . dechex($this->colorB);
        return $result;
    }
}
