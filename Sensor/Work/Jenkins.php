<?php

namespace Sensor\Work;

use \Sensor\SensorAbstract;
use \Sensor\CacheableInterface;

/**
 * Jira worklog helper
 *
 * @category    Sensor
 * @package     Sensor_Work
 * @author      Evgeniy Vasilev <e.vasilev@office.ngs.ru>
 */
class Jenkins extends SensorAbstract implements CacheableInterface
{
    /**
     * @param array|null $config
     */
    public function __construct($config)
    {
        $config = array_merge(['path' => '', 'user' => '', 'password' => '', 'response_cache' => 30], $config);
        $this->cacheKey .= $config['job'];

        parent::__construct($config);
    }


    /**
     * @return string
     */
    public function result() {
        $callback = function () {
            $url = $this->config['url'] . '/api/json?pretty=true';

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

        if (!isset($response['lastFailedBuild']['number']) || !isset($response['lastCompletedBuild']['number'])) {
            return null;
        }

        $this->meta = [];

        if ($response['lastFailedBuild']['number'] == $response['lastCompletedBuild']['number']) {
            $this->meta['number'] = $response['lastFailedBuild']['number'];
            $this->meta['status'] = 'last';

            return $this->config['job'];
        }
    }
}
