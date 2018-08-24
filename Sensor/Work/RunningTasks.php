<?php

namespace Sensor\Work;

use \Sensor\SensorAbstract;

/**
 * Checks for any task running.
 * Helps find crashed lsync, webpack and any other watchers
 *
 * @category    Sensor
 * @package     Sensor_Work
 * @author      Evgeniy Vasilev <e.vasilev@office.ngs.ru>
 */
class RunningTasks extends SensorAbstract
{
    /**
     * @param array|null $config
     */
    public function __construct($config)
    {
        $config = array_merge(['cache_time' => 1, 'tasks' => []], $config);

        parent::__construct($config);
    }


    /**
     * @return string
     */
    public function result() {
        $this->color = null;
        $errors = [];

        foreach ($this->config['tasks'] as $taskName => $taskCheck) {
            if (empty($taskCheck) || $status = exec($taskCheck)) {
                continue;
            }

            $errors[] = $taskName;
        }

        if (empty($errors)) {
            return '';
        }

        $result = '';

        $this->color = '#ff5555';

        if (count($errors) <= 3 ) {
            $result .= 'failed: ' . implode('; ', $errors);
        } else {
            $result = 'too many down tasks';
        }

        return $result;
    }
}
