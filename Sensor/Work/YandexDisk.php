<?php

/**
 * Jira worklog helper
 *
 * @category    Sensor
 * @package     Sensor_Work
 * @author      Evgeniy Vasilev <e.vasilev@office.ngs.ru>
 */
class Sensor_Work_YandexDisk extends Sensor_Abstract
{
    protected $diskStatus = false;


    /**
     * @param array|null $config
     */
    public function __construct($config)
    {
        $config = array_merge(['cache_time' => 1], $config);

        if ($disk = exec('which yandex-disk')) {
            $this->diskStatus = true;
        }

        parent::__construct($config);
    }


    /**
     * @return string
     */
    public function result() {
        if (!$this->diskStatus) {
            return 'diskstatus error';
        }

        if (!$allStatus = $this->getStatus()) {
            return 'getstatus error';
        }

        list ($status, $progress) = $allStatus;
        $result = 'Ya.disk ●';

        if ($status == 'idle' ) {
            $this->color = '#00cc00';
        } elseif ($status == 'busy' || $status = 'index') {
            $this->color = '#cccc00';
            if ($progress) {
                $result .= ' ⇅' . $progress .'%';
            }
        } else {
            $this->color = '#cc0000';
            $result .= $status;
            if ($progress) {
                $result .= ' ⇅' . $progress .'%';
            }
        }

        return $result;
    }


    /**
     * Получить состояние Яндекс-диска
     */
    protected function getStatus()
    {
        $result = ['status' => null, 'progress' => null];

        exec('yandex-disk status', $status);
        $status = implode("\n", $status);

        if (!preg_match('/Synchronization core status\: ([^\n]+)/us', $status, $matches)) {
            file_put_contents("/tmp/status.txt", $status);
            var_dump('error sync status');
            return array_values($result);
        }

        $result['status'] = $matches[1];

        if (preg_match('/Sync progress\: [^\n]+ \(([0-9]{1,3}) %\)/us', $status, $matches)) {
            $result['progress'] = $matches[1];
        }

        return array_values($result);
    }
}
