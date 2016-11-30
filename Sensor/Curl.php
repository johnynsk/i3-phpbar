<?php

namespace Sensor;

/**
 * Хелпер для curl
 */
class Curl
{
    /**
     * Получить данные по ресурсу
     *
     * @param string $url
     * @param array $options
     *
     * @return string mixed
     */
    public function get($url, $options = [])
    {
        $allOptions = [
                CURLOPT_URL => $url,
                CURLOPT_USERAGENT => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.99 Safari/537.36',
                CURLOPT_TIMEOUT => 3,
                CURLOPT_RETURNTRANSFER => true,
            ];
        foreach ($options as $key => $value) {
            $allOptions[$key] = $value;
        }

        $ch = curl_init();

        foreach ($allOptions as $option => $value) {
            $option = str_replace('option', '', $option);
            curl_setopt($ch, $option, $value);
        }

        return curl_exec($ch);
    }
}
