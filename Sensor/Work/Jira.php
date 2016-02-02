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

        $response = json_decode($json);

        if (!$response) {
            return 'Нет данных';
        } elseif (!$response->total) {
            return 'Нет задач';
        } elseif ($response->total > 1) {
            return 'Задач: ' . $response->total;
        } else {
            $issue = reset($response->issues);
            return $issue->key;
        }
    }
}
