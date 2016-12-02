<?php

namespace Sensor\Conditions;

use \Sensor\SensorAbstract;
use \Sensor\CacheableInterface;

/**
 * Датчик углекислого газа
 *
 * @category    Sensor
 * @package     Sensor\Conditions
 * @author      Evgeniy Vasilev <e.vasilev@office.ngs.ru>
 */
class Co2 extends SensorAbstract implements CacheableInterface
{
    /**
     * Цветовые схемы
     *
     * @var array
     */
    protected $colors = ['normal' => '#66ff66', 'warning' => '#ffaa00', 'error' => '#ff5500'];


    /**
     * Текущее значение датчика
     *
     * @var int
     */
    protected $value;


    /**
     * конструктор
     *
     * @param array|null $config
     */
    public function __construct($config)
    {
        $config = array_merge(['value' => 800, 'critical_value' => 1150], $config);
        parent::__construct($config);

        $this->value = $config['value'];
    }


    /**
     * Получение данных и вывод
     *
     * @return string
     */
    public function result()
    {
        $res = $this->di['curl']->get($this->config['api']);

        if (!$val = json_decode($res, true)) {
            return ' - ';
        }

        if(!isset($val['carbonDioxide']['advertisedValue'])) {
            return ' - ';
        }

        $oldValue = $this->value;
        $this->value = (int)$val['carbonDioxide']['advertisedValue'];

        if ($this->value > $oldValue * 2) {
            $this->_sendMessage('Хватит дышать на датчик!');
        } elseif ($this->value > $this->config['critical_value']) {
            $this->_postCacheMessage();
        }

        if ($this->value < 500) {
            $this->color = $this->colors['normal'];
        } elseif ($this->value < 800) {
            $this->color = $this->colors['warning'];
        } else {
            $this->color = $this->colors['error'];
        }

        $result = $this->value . 'ppm';

        return $result;
    }


    /**
     * Отправка сообщения с таймаутом через кэш
     */
    protected function _postCacheMessage()
    {
        $value = $this->value;

        $sendMessage = function () use ($value) {
            $text = '' . $value . ' ppm :dash:';

            if ($value > 1100 && $value < 1200) {
                $text = $value .' ppm! :sleeping:';
            } elseif ($value > 1200 && $value <= 1350) {
                $text = $value .' ppm! :skull:';
            } elseif ($value > 1350) {
                $text = $value . ' ppm! :rip:';
            }

            return $this->_sendMessage($text);
        };

        $this->di['cache']->get($this->cacheKey . '.slack', $sendMessage, $this->config['message_cache_time']);
    }


    /**
     * Отправка сообщения
     * @param string $text
     * @return bool
     */
    protected function _sendMessage($text)
    {
        if (empty($this->config['send_messages'])) {
            return false;
        }

        $settings = [
            'username' => $this->config['username'],
            'token' => $this->config['token'],
            'channel' => $this->config['channel'],
            'text' => $text
        ];

        $this->di['curl']->get('https://slack.com/api/chat.postMessage?' . http_build_query($settings));
        return true;
    }
}
