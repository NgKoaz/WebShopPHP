<?php

namespace App\core;

use App\exceptions\Container\ContainerException;
use ReflectionClass;

class ServiceHolder
{
    private mixed $instance;

    public function __construct(private mixed $className, private bool $isSingleton, private Container $container) {}

    public function getInstance()
    {
        if (!$this->isSingleton) return $this->container->create($this->className);
        if (isset($this->instance)) return $this->instance;
        return $this->instance = $this->container->create($this->className);
    }
}
