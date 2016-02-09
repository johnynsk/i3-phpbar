<?php

/**
 * Jira worklog helper
 *
 * @category    Sensor
 * @package     Sensor_Audio
 * @author      Evgeniy Vasilev <e.vasilev@office.ngs.ru>
 */
class Sensor_Work_Jira extends Sensor_Abstract
{
    /**
     * @param array|null $config
     */
    public function __construct($config)
    {
        parent::__construct($config);

        $this->config = array_merge(['host' => '', 'user' => '', 'password' => '', 'cache_time' => 30], $this->config);
    }


    /**
     * @return string
     */
    public function result() {
        $url = 'http://' . $this->config['host'] . '/rest/api/2/search?' . http_build_query(['jql' => "labels = jwh:{$this->config['user']}:in-work"]);
        $json = $this->di->getCurl()->get(
            $url,
            [
                CURLOPT_USERPWD => "{$this->config['user']}:{$this->config['password']}",
                CURLOPT_HTTPAUTH => CURLAUTH_BASIC
            ]
        );

        $response = json_decode($json, true);

        if (!$response) {
            return 'Нет данных';
        } elseif (!$response['total']) {
            return 'Нет задач';
        } elseif ($response['total'] > 1) {
            return 'Задач: ' . $response['total'];
        } else {
            $issue = reset($response['issues']);
            if ($time = $this->_getTimeSpent($issue)) {
                return $issue['key'] . ' @ ' . $time;
            }
            return $issue['key'];
        }
    }


    /**
     * Расчет времени работы над задачей
     *
     * @param $issue
     * @return int|null|string
     */
    protected function _getTimeSpent($issue)
    {
        $timeStart = null;
        $timeSpent = null;

        foreach ($issue['fields']['labels'] as $label) {
            if (!preg_match('#^jwh:' . preg_quote($this->config['user']) . ':([0-9]+)$#', $label, $matches)) {
                continue;
            }

            $timeStart = $matches[1];
            $timeSpent = time() - $timeStart;
        }

        if (!$timeSpent) {
            return $timeSpent;
        }
        return $this->_formatTimeDiff($timeSpent);
    }


    /**
     * Форматирование времени
     *
     * @param $timeDiff
     * @return string
     */
    private function _formatTimeDiff($timeDiff)
    {
        if ($timeDiff < 60) {
            return (int)$timeDiff . 's';
        } elseif ($timeDiff < 3600) {
            return (int)($timeDiff / 60) . 'm ' . (int)($timeDiff % 60) . 's';
        }

        return (int)($timeDiff / 3600) . 'h ' . (int)($timeDiff % 3600 / 60) . 'm';
    }
}
