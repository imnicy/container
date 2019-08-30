<?php

namespace Nicy\Container\Concerns;

use Nicy\Container\ServiceProvider;

trait ServiceProviderTrait
{
    /**
     * Indicates if the manager has "booted".
     *
     * @var bool
     */
    protected $booted = false;

    /**
     * The loaded service providers.
     *
     * @var array
     */
    protected $loadedProviders = [];

    /**
     * Register a service provider with the manager.
     *
     * @param  \Nicy\Container\ServiceProvider|string  $provider
     * @return \Nicy\Container\ServiceProvider|void
     */
    public function register($provider)
    {
        if (! $provider instanceof ServiceProvider) {
            $provider = new $provider($this->driver());
        }

        if (array_key_exists($providerName = get_class($provider), $this->loadedProviders)) {
            return;
        }

        $this->loadedProviders[$providerName] = $provider;

        if (method_exists($provider, 'register')) {
            $provider->register();
        }

        if ($this->booted) {
            $this->bootProvider($provider);
        }
    }

    /**
     * Boots the registered providers.
     */
    public function boot()
    {
        if ($this->booted) {
            return ;
        }

        array_walk($this->loadedProviders, function ($p) {
            $this->bootProvider($p);
        });

        $this->booted = true;
    }

    /**
     * Boot the given service provider.
     *
     * @param  \Nicy\Container\ServiceProvider  $provider
     * @return mixed|void
     */
    protected function bootProvider(ServiceProvider $provider)
    {
        if (method_exists($provider, 'boot')) {
            return $this->driver()->call([$provider, 'boot']);
        }
    }
}