<?php
/**
 * Simple dependency injection
 */
$di = new Sensor_Di();

$di->set('cache', function () use ($di, $config) {
        $instance = new Sensor_Cache($config);

        return $instance;
    }
);

$di->set('iterator', function () use ($di, $config) {
        $iterator = new Panel_Iterator($config);
        $iterator->setCache($di->getCache());

        return $iterator;
    }
);

$di->set('curl', function () use ($di, $config) {
        $curl = new Sensor_Curl($config);

        return $curl;
    }
);