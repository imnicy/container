<?php

namespace Nicy\Container;

use DI\ContainerBuilder;
use DI\Container as PsrContainer;
use Nicy\Container\Contracts\Container;
use Nicy\Container\Drivers\DiContainer;
use Nicy\Support\Manager as ManagerSupport;

final class Manager extends ManagerSupport
{
    /**
     * @var string
     */
    protected $default;

    /**
     * @var array
     */
    protected $options;

    /**
     * Manager constructor.
     *
     * @param string $default
     * @param array $options
     */
    public function __construct($default='php-di', $options=[])
    {
        $this->default = $default;
        $this->options = $options;
    }

    /**
     * Get the default driver name.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        return $this->default;
    }

    /**
     * Set the default driver name.
     *
     * @param string $default
     */
    public function setDefaultDriver($default)
    {
        $this->default = $default;
    }

    /**
     * Builder the DiContainer driver
     *
     * @return PsrContainer
     */
    protected function createPHPDiDriver()
    {
        $container = $this->createPHPDiContainerBuilder()->build();

        $container->set('container', $container);
        $container->set(Container::class, $container);

        return $container;
    }

    /**
     * Create the php-di container builder
     *
     * @return ContainerBuilder
     */
    protected function createPHPDiContainerBuilder()
    {
        return new ContainerBuilder(DiContainer::class);
    }
}