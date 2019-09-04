<?php

namespace Nicy\Container\Drivers;

use Closure;
use DI\Container as PsrContainer;
use Nicy\Container\Contracts\Container as ContainerContracts;

class DiContainer extends PsrContainer implements ContainerContracts
{
    /**
     * The service binding methods that have been executed.
     *
     * @var array
     */
    protected $ranServiceBinders = [];

    public function singleton($name, $value)
    {
        $this->set($name, $value);
    }

    public function get($name)
    {
        if (! $this->has($name) &&
            array_key_exists($name, $this->availableBindings) &&
            ! array_key_exists($name, $this->ranServiceBinders)) {

            if (is_callable($callable = $this->availableBindings[$name])) {
                $this->call($callable);
            }

            $this->ranServiceBinders[$name] = true;
        }

        return parent::get($name);
    }

    public function withBindings(array $bindings = [])
    {
        $this->availableBindings = $bindings;
    }

    /**
     * Determine if a given offset exists.
     *
     * @param  string  $key
     * @return bool
     */
    public function offsetExists($key)
    {
        return $this->has($key);
    }

    /**
     * Get the value at a given offset.
     *
     * @param  string  $key
     * @return mixed
     */
    public function offsetGet($key)
    {
        return $this->get($key);
    }

    /**
     * Set the value at a given offset.
     *
     * @param  string  $key
     * @param  mixed   $value
     * @return void
     */
    public function offsetSet($key, $value)
    {
        $this->set($key, $value instanceof Closure ? $value : function () use ($value) {
            return $value;
        });
    }

    /**
     * Unset the value at a given offset.
     *
     * @param  string  $key
     * @return void
     */
    public function offsetUnset($key)
    {
        unset($this->resolvedEntries[$key]);
    }

    /**
     * Dynamically access container services.
     *
     * @param  string  $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this[$key];
    }

    /**
     * Dynamically set container services.
     *
     * @param  string  $key
     * @param  mixed   $value
     * @return void
     */
    public function __set($key, $value)
    {
        $this[$key] = $value;
    }

    /**
     * The available container bindings and their respective load methods.
     *
     * @var array
     */
    public $availableBindings = [];
}