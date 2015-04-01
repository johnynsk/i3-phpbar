<?php
class Imap extends Cli_Abstract{
    var $cacheKey = "local.bar.imap";
    var $cacheTime = 30;

    var $color = '';
    var $colorR = 255;
    var $colorG = 255;
    var $colorB = 255;
    var $step = 8;


    function result()
    {
        $result = exec("~/.i3/scripts/imap.py/imap.py --inline");
        $result = '@' . $result;
        return $result;
    }
}
