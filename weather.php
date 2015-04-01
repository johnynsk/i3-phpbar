<?php
class Weather extends Cli_Abstract{
    var $cacheKey = 'local.temp';
    var $cacheTime = 300;

    var $color = '#66ff66';

    function result()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://pogoda.ngs.ru/');
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.99 Safari/537.36");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $res = curl_exec($ch);

        preg_match('#"value__main">(.*?)<#usi', $res, $m);

        $val = $m[1];
        
        if(empty($val)) {
            return '';
        }

        $val .= '°C';
        $val = str_replace('&minus;', '-', $val);

        return $val;
    }
}
