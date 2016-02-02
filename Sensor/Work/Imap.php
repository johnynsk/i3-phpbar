<?php
class Sensor_Work_Imap extends Sensor_Abstract
{
    var $cacheKey = 'local.bar.imap';
    var $cacheTime = 30;


    /**
     * @return string
     */
    public function result()
    {
        $result = exec('~/.i3/scripts/imap.py/imap.py --inline');
        $result = '@' . $result;
        return $result;
    }
}
