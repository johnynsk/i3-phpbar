<?php

/**
 * Dependency injections
 *
 * @method Sensor_Curl getCurl()
 * @method Panel_Iterator getIterator()
 * @method Sensor_Cache getCache()
 */
class Sensor_Di
{
    /**
     * созданные объекты
     *
     * @var
     */
    protected $instances = [];


    /**
     * callback для создания ресурсов
     *
     * @var callable[]
     */
    protected $callbacks = [];


    /**
     * Задает ресурс и коллбек создания
     *
     * @param string $name
     * @param callable $callback
     * @return $this
     */
    public function set($name, $callback)
    {
        $this->callbacks[$name] = $callback;

        return $this;
    }


    /**
     * Магия по возвращению ресурса
     *
     * @param string $name
     * @param array $arguments
     * @return mixed
     *
     * @throws Sensor_Di_Exception
     */
    public function __call($name, $arguments = [])
    {
        if (!preg_match('/^get(.*)/', $name, $matches)) {
            throw new Sensor_Di_Exception('Неизвестный метод');
        }

        return $this->get($matches[1], $arguments);
    }

    /**
     * Возвращает новый ресурс
     *
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public function getNew($name, $arguments = [])
    {
        return $this->get($name, $arguments, true);
    }


    /**
     * Возвращает ресурс
     *
     * @param $name
     * @param array $arguments
     * @param bool $new
     * @throws Sensor_Di_Exception
     * @return mixed
     */
    public function get($name, $arguments = [], $new = false)
    {
        $name = mb_strtolower(mb_substr($name, 0, 1)) . mb_substr($name, 1);

        if (!$new) {
            if (isset($this->instances[$name])) {
                return $this->instances[$name];
            } elseif (!isset($this->callbacks[$name])) {
                throw new Sensor_Di_Exception('Нет инициализации ресурса ' . $name);
            }
        }

        if (is_callable($this->callbacks[$name])) {
            $instance = call_user_func_array($this->callbacks[$name], $arguments);
        } elseif (is_scalar($this->callbacks[$name])) {
            $instance = new $this->callbacks[$name]();

            if (method_exists($this->callbacks[$name], 'setConfig')) {
                $this->callbacks[$name]->setConfig($this->getConfig());
            }
        } else {
            throw new Sensor_Di_Exception('Не удалось инициализировать ресурс ' . $name);
        }

        if ($new) {
            return $instance;
        }

        $this->instances[$name] = $instance;
        return $instance;
    }
}