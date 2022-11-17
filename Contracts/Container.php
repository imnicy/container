<?php

namespace Nicy\Container\Contracts;

use ArrayAccess;
use Psr\Container\ContainerInterface;

interface Container extends ArrayAccess, ContainerInterface
{
    public function singleton($name, $value=null);

    public function get($name);

    public function make($name, $parameters=[]);

    public function set($name, $value);

    public function has($name);

    public function call($callable, $parameters=[]);
}