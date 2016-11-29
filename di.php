<?php
/**
 * Simple dependency injection
 */
namespace Sensor;

$di = new Di();

$di['cache'] = function () use ($di, $config) {
    $instance = new Cache($config);

    return $instance;
};

$di['iterator'] = function () use ($di, $config) {
    $iterator = new \Panel\Iterator($config);
    $iterator->setCache($di['cache']);

    return $iterator;
};

$di['curl'] = function () use ($di, $config) {
    $curl = new Curl($config);

    return $curl;
};

