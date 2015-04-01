<?php
class Currency extends Cli_Abstract{
    var $cacheTime = 600;
    var $cacheKey = 'local.bar.currency';

    function result() {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://www.micex.ru/markets/currency/today/index_html?__retry__=1&__node__=node4:10012');
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.99 Safari/537.36");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $res = curl_exec($ch);

        preg_match('#\\\"USDRUB_TOM\ \-\ USD\/RUB\\\", \\\"LAST\\\"\: (.*?),#usi', $res, $usd);
        preg_match('#\\\"EURRUB_TOM\ \-\ EUR\/RUB\\\", \\\"LAST\\\"\: (.*?),#usi', $res, $eur);

        if (empty((float)$usd[1]) || empty((float)$eur[1])) {
            return 'Нет данных';
        }

        $val = (float)$usd[1] . '$ / ';
        $val .= (float)$eur[1] . '€';

        return $val;

    }
}
