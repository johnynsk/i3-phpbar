<?php
/**
 * Основной интерпретатор
 */
require_once 'bootstrap.php';
require_once 'di.php';

$iterator = $di->getIterator();

foreach ($config['items'] as $itemConfig) {
    if (!isset($itemConfig['class']) || (isset($itemConfig['enabled']) && empty($itemConfig['enabled']))) {
        continue;
    }

    $className = 'Sensor_' . $itemConfig['class'];
    if (!class_exists($className)) {
        continue;
    }

    $options = isset($itemConfig['options']) ? $itemConfig['options'] : [];
    $instance = new $className($options);

    if (method_exists($instance, 'setDi')) {
        $instance->setDi($di);
    }

    $iterator->add($instance);
}

echo '{"version":1}'."\n[\n[]\n";
echo ',['.json_encode([['full_text' => 'loading ...']]) .']' . PHP_EOL;
while (true)
{
    $result = $iterator->result();
    echo ',[' . json_encode($result) . ']' . PHP_EOL;
    usleep($config['interval'] * 1000);
}
