<?php

namespace Sensor\Work;

use \Sensor\SensorAbstract;
use \Sensor\CacheableInterface;

/**
 * Jira worklog helper
 *
 * @category    Sensor
 * @package     Sensor_Audio
 * @author      Evgeniy Vasilev <e.vasilev@office.ngs.ru>
 */
class Jira extends SensorAbstract implements CacheableInterface
{
    /**
     * @param array|null $config
     */
    public function __construct($config)
    {
        $config = array_merge(['host' => '', 'user' => '', 'password' => '', 'response_cache' => 30], $config);

        parent::__construct($config);
    }


    /**
     * @return string
     */
    public function result() {
        $callback = function () {
            $url = 'http://' . $this->config['host'] . '/rest/api/2/search?' . http_build_query(['jql' => $this->config['query']]);

            $json = $this->di['curl']->get(
                $url,
                [
                    CURLOPT_USERPWD => "{$this->config['user']}:{$this->config['password']}",
                    CURLOPT_HTTPAUTH => CURLAUTH_BASIC
                ]
            );

            return json_decode($json, true);
        };

        $response = $this->di['cache']->get($this->cacheKey . '_response', $callback, $this->config['response_cache']);

        if (isset($this->config['colorify']) && is_callable($this->config['colorify'])) {
            $this->color = $this->config['colorify']($this);
        }

        $prefix = !empty($this->config['prefix']) ? $this->config['prefix'] : '';
        if (!$response) {
            return !empty($this->config['empty']) ? $this->config['empty'] : null;
        } elseif (!$response['total']) {
            return !empty($this->config['empty']) ? $this->config['empty'] : null;
        } elseif ($response['total'] > 1 && $response['total'] <= 5) {
            $buffer = [];

            foreach ($response['issues'] as $issue) {
                $buffer[] = $issue['key'];
            }

            return $prefix . implode(' | ', $buffer);
        } elseif ($response['total'] > 5) {
            return $prefix . $response['total'];
        } else {
            $issue = reset($response['issues']);
            if ($time = $this->_getTimeSpent($issue)) {
                return $prefix . $issue['key'] . ' @ ' . $time;
            }
            return $prefix . $issue['key'];
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

        if (empty($issue['fields']['labels'])) {
            return null;
        }

        foreach ($issue['fields']['labels'] as $label) {
            if (!preg_match('#^jwh:' . preg_quote($this->config['user']) . ':([0-9]+)$#', $label, $matches)) {
                continue;
            }

            $timeStart = $matches[1];
            $timeSpent = time() - $timeStart;
        }

        if (!$timeSpent) {
            return null;
        }

        return Helper_Time::format($timeSpent);
    }
}
