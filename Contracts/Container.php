<?php

namespace Nicy\Container\Contracts;

use ArrayAccess;
use Psr\Container\ContainerInterface;

interface Container extends ArrayAccess, ContainerInterface
{
    public function singleton($name, $value);

    public function get($name);

    public function make($name, array $parameters = []);

    public function set(string $name, $value);

    public function has($name);

    public function call($callable, array $parameters = []);
}