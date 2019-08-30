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

    public function register()
    {
        //
    }
}