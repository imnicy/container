<?php

namespace Nicy\Container\Contracts;

interface Provider
{
    /**
     * Register the provider
     *
     * @return void
     */
    public function register();
}