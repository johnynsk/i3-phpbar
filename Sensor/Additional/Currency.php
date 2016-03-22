<?php

/**
 * Курсы валют.
 * Парсится страница moex.ru
 *
 * @category    Sensor
 * @package     Sensor_Additional
 * @author      Evgeniy Vasilev <e.vasilev@office.ngs.ru>
 */
class Sensor_Additional_Currency extends Sensor_Abstract
{
    /**
     * Текущее значение
     *
     * @param array
     */
    protected $value = [];


    /**
     * Конструктор
     *
     * @param array|null $config
     */
    public function __construct($config)
    {
        parent::__construct($config);
    }


    /**
     * Получает курсы валют
     *
     * @return string
     */
    public function result() {
        $xml = $this->di->getCurl()->get('http://www.micex.ru/markets/currency/today/index_html?__retry__=1&__node__=node4:10012');

        preg_match('#\\\"USDRUB_TOM\ \-\ USD\/RUB\\\", \\\"LAST\\\"\: (.*?),#usi', $xml, $usd);
        preg_match('#\\\"EURRUB_TOM\ \-\ EUR\/RUB\\\", \\\"LAST\\\"\: (.*?),#usi', $xml, $eur);

        if (empty($usd[1]) || empty($eur[1])) {
            return 'Нет данных';
        }

        $this->value = ['usd' => (float)$usd[1], 'eur' => (float)$eur[1]];
        $this->_writeHistory();

        return $this->value['usd'] . '$ / ' . $this->value['eur'] . '€';
    }


    /**
     * Запись данных истории
     */
    protected function _writeHistory()
    {
        if (!isset($this->config['history_path'])) {
            return;
        }

        $data = $this->value;
        $data['date'] = time();

        file_put_contents($this->config['history_path'], json_encode($data) . ",\n", FILE_APPEND);
    }
}
