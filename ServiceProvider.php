<?php

namespace Nicy\Container;

use Nicy\Container\Contracts\Provider;

abstract class ServiceProvider implements Provider
{
    /**
     * The manager instance being facaded.
     *
     * @var \Nicy\Container\Contracts\Container
     */
    protected $container;

    /**
     * ServiceProvider constructor.
     *
     * @param $container
     */
    public function __construct($container)
    {
        $this->container = $container;
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Merge the given configuration with the existing configuration.
     *
     * @param  string  $path
     * @param  string  $key
     * @return void
     */
    protected function mergeConfigFrom($path, $key)
    {
        $config = $this->container['config']->get($key, []);

        $this->container['config']->set($key, array_merge(require $path, $config));
    }
}