<?php

namespace Nicy\Container\Drivers;

use Closure;
use DI\Container as PsrContainer;
use Nicy\Container\Contracts\Container as ContainerContracts;

class DiContainer extends PsrContainer implements ContainerContracts
{
    /**
     * Define an object or a value in the container.
     *
     * @param string $name Entry name
     * @param mixed|null $value Value, use definition helpers to define objects
     */
    public function singleton($name, $value=null)
    {
        if (is_null($value)) {
            $value = $name;
        }

        $this->set($name, $value);
    }

    /**
     * Define an object or a value in the container.
     *
     * @param string $name Entry name
     * @param mixed $value Value, use definition helpers to define objects
     */
    public function set($name, $value)
    {
        if (is_string($value) && class_exists($value)) {
            $value = $this->make($value);
        }

        parent::set($name, $value);
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
}