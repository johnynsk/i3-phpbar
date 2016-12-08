<?php
/**
 * Подключение классов
 *
 * @param string $className
 */
spl_autoload_register(function ($className) {
        $fileName = $className;
        $fileName = preg_replace('/_/', '/', $fileName);
        $fileName = preg_replace('/\\\\/', '/', $fileName);
        $fileName = __DIR__ . DIRECTORY_SEPARATOR . $fileName . '.php';
        if (file_exists($fileName)) {
            return require $fileName;
        }
    }
);

