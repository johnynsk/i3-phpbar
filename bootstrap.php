<?php
/**
 * Подключение классов
 *
 * @param string $className
 */
function __autoload($className)
{
    $fileName = preg_replace('#_#', '/', $className);
    require_once __dir__ . '/' . $fileName . '.php';
}

$config = require 'config.php';
