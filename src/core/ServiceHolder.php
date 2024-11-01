<?php

namespace App\core;

use App\exceptions\Container\ContainerException;
use ReflectionClass;

class ServiceHolder
{
    private mixed $instance;

    public function __construct(private string $className, private bool $isSingleton, private Container $container) {}

    // public function create()
    // {
    //     $reflection = new ReflectionClass($this->className);
    //     $contructor = $reflection->getConstructor();
    //     if ($contructor == null) return new $this->className;
    //     $params = $contructor->getParameters();
    //     if (count($params) == 0) return new $this->className;

    //     // There are params in constructor, we need to pass all of these from container.
    //     $dependencies = [];
    //     foreach ($params as $param) {
    //         if (!$param->hasType())
    //             throw new ContainerException("Param [$" . $param->getName() . "] doesn't have type hint!");
    //         $type = $param->getType();
    //         $dependencies[] = $this->container->get($type);
    //     }
    //     return $reflection->newInstanceArgs($dependencies);
    // }

    public function getInstance()
    {
        if (!$this->isSingleton) return $this->container->create($this->className);
        if (isset($this->instance)) return $this->instance;
        return $this->instance = $this->container->create($this->className);
    }
}
