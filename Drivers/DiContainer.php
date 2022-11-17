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
     * @param string $name      Entry name
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
     * Build an entry of the container by its name.
     *
     * This method behave like get() except resolves the entry again every time.
     * For example if the entry is a class then a new instance will be created each time.
     *
     * This method makes the container behave like a factory.
     *
     * @param string $name       Entry name or a class name.
     * @param array $parameters  Optional parameters to use to build the entry. Use this to force
     *                           specific parameters to specific values. Parameters not defined in this
     *                           array will be resolved using the container.
     * @return mixed
     */
    public function make($name, $parameters=[])
    {
        return parent::make($name, $parameters);
    }

    /**
     * Call the given function using the given parameters.
     *
     * Missing parameters will be resolved from the container.
     *
     * @param callable $callable   Function to call.
     * @param array $parameters    Parameters to use. Can be indexed by the parameter names
     *                             or not indexed (same order as the parameters).
     *                             The array can also contain DI definitions, e.g. DI\get().
     *
     * @return mixed Result of the function.
     */
    public function call($callable, $parameters=[])
    {
        return parent::call($callable, $parameters);
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
     * @param string $key
     * @return bool
     */
    public function offsetExists($key)
    {
        return $this->has($key);
    }

    /**
     * Get the value at a given offset.
     *
     * @param string $key
     * @return mixed
     */
    public function offsetGet($key)
    {
        return $this->get($key);
    }

    /**
     * Set the value at a given offset.
     *
     * @param string $key
     * @param mixed $value
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
     * @param string $key
     * @return void
     */
    public function offsetUnset($key)
    {
        unset($this->resolvedEntries[$key]);
    }

    /**
     * Dynamically access container services.
     *
     * @param string $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this[$key];
    }

    /**
     * Dynamically set container services.
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function __set($key, $value)
    {
        $this[$key] = $value;
    }
}