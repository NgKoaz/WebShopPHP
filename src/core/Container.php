<?php

namespace App\core;

use Psr\Container\ContainerInterface;
use App\exceptions\Container\NotFoundContainerException;
use App\exceptions\Container\ContainerException;
use ReflectionClass;

class Container implements ContainerInterface
{
    /**
     * @var ServiceHolder[]
     */
    private array $services = [];

    private static Container $instance;

    public static function getInstance(): Container
    {
        if (isset(self::$instance)) return self::$instance;
        return self::$instance = new Container();
    }

    private function checkValidParams(string $id, string $className)
    {
        if (!(class_exists($id) || interface_exists($id)))
            throw new NotFoundContainerException("Class or Interface is not found! [$id]");
        if (!class_exists($className))
            throw new NotFoundContainerException("Class is not found! [$className]");
        if (interface_exists($className))
            throw new ContainerException("You need to pass a classname not an interface! [$className]");
    }

    public function addSingleton(string $id, string $className): Container
    {
        $this->checkValidParams($id, $className);
        $this->services[$id] = new ServiceHolder($className, true, $this);
        return $this;
    }

    public function addTransient(string $id, string $className): Container
    {
        $this->checkValidParams($id, $className);
        $this->services[$id] = new ServiceHolder($className, false, $this);
        return $this;
    }

    public function create(string $className)
    {
        // 1. Check contruct function and get params.
        // 2. Traverse params, create new object for contruct's needs.
        if (!class_exists($className))
            throw new NotFoundContainerException("Class is not found! [$className]");
        $reflection = new ReflectionClass($className);
        $contructor = $reflection->getConstructor();
        if ($contructor == null) return new $className;
        $params = $contructor->getParameters();
        if (count($params) == 0) return new $className;

        // There are params in constructor, we need to pass all of these from container.
        $dependencies = [];
        foreach ($params as $param) {
            if (!$param->hasType())
                throw new ContainerException("Param [$" . $param->getName() . "] doesn't have type hint!");
            $type = $param->getType();
            $dependencies[] = $this->get($type);
        }
        return $reflection->newInstanceArgs($dependencies);
    }

    public function get(string $id): mixed
    {
        if (!$this->has($id)) throw new NotFoundContainerException("Container doesn't have $id");
        $serviceHolder = $this->services[$id];
        return $serviceHolder->getInstance();
    }

    public function has(string $id): bool
    {
        return isset($this->services[$id]);
    }
}
