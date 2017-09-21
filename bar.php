<?php
/**
 * Основной интерпретатор
 */
if (isset($_SERVER['HOME']) && file_exists($_SERVER['HOME'] . '/.config/i3-phpbar/config.php')) {
    $userPath = $_SERVER['HOME'] . '/.config/i3-phpbar';
    $config = require $userPath . '/config.php';

    if (file_exists($userPath . '/vendor/autoload.php')) {
        require $userPath . '/vendor/autoload.php';
    }
} else {
    $config = require $userPath . '/config.php';
}

require_once 'vendors/autoload.php';
require_once 'bootstrap.php';
require_once 'di.php';

$iterator = $di['iterator'];

foreach ($config['items'] as $itemConfig) {
    if (!isset($itemConfig['class']) || (isset($itemConfig['enabled']) && empty($itemConfig['enabled']))) {
        continue;
    }

    $className = $itemConfig['class'];

    if (!class_exists($className)) {
        echo "class " . $className . " does not exists\n";

        die;
    }

    $options = isset($itemConfig['options']) ? $itemConfig['options'] : [];
    $instance = new $className($options);

    if (method_exists($instance, 'setDi')) {
        $instance->setDi($di);
    }

    $iterator->add($instance);
}

echo '{"version":1}'."\n[\n[]\n";

while (true)
{
    $result = $iterator->result();
    echo ',' . json_encode($result) . PHP_EOL;
    usleep($config['interval'] * 1000);
}
