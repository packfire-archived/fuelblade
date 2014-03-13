<?php

/**
 * Packfire FuelBlade
 * By Sam-Mauris Yong
 *
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\FuelBlade;

use \Closure;

/**
 * The dependency injection / IoC container
 *
 * @author Sam-Mauris Yong <sam@mauris.sg>
 * @copyright Sam-Mauris Yong <sam@mauris.sg>
 * @license http://www.opensource.org/licenses/BSD-3-Clause The BSD 3-Clause License
 * @package Packfire\FuelBlade
 * @since 1.0.0
 */

class Container implements ContainerInterface, \ArrayAccess
{
    /**
     * The values stored in the IoC container
     * @var array
     * @since 1.0.0
     */
    protected $values = array();

    /**
     * Provides implementation of offsetExists in \ArrayAccess
     * @internal
     * @param string $id
     * @return boolean
     * @since 1.0.0
     */
    public function offsetExists($id)
    {
        return isset($this->values[$id]);
    }

    /**
     * Provides implementation of offsetGet in \ArrayAccess
     * @internal
     * @param string $id
     * @return mixed
     * @since 1.0.0
     */
    public function offsetGet($id)
    {
        if (!isset($this->values[$id])) {
            throw new \InvalidArgumentException(sprintf('"%s" is not defined in FuelBlade IoC container.', $id));
        }

        $value = $this->values[$id];
        return $this->loadValue($value);
    }

    /**
     * Load a value to see if it can be invoked to get the actual value
     * @param mixed $value The value to be loaded
     * @return mixed Returns the value loaded
     * @since 1.0.0
     */
    protected function loadValue($value)
    {
        if (is_object($value) && method_exists($value, '__invoke')) {
            return $value($this);
        } else {
            return $value;
        }
    }

    /**
     * Provides implementation of offsetSet in \ArrayAccess
     * @internal
     * @param string $id
     * @param mixed $value
     * @since 1.0.0
     */
    public function offsetSet($id, $value)
    {
        $this->values[$id] = $value;
    }

    /**
     * Provides implementation of offsetUnset in \ArrayAccess
     * @internal
     * @param string $id
     * @since 1.0.0
     */
    public function offsetUnset($id)
    {
        unset($this->values[$id]);
    }

    /**
     * Create a function for cloning an object
     * @param object $object The object to be cloned when the function is called
     * @return Closure Returns the anonymous function that clones the object
     * @since 1.0.0
     */
    public function copy($object)
    {
        return function () use ($object) {
            return clone $object;
        };
    }

    /**
     * Create a function for storing a Closure
     * @param Closure The closure to be stored.
     * @return Closure Returns the anonymous function that stores the original Closure
     * @since 1.0.0
     */
    public function func(Closure $callable)
    {
        return function () use (&$callable) {
            return $callable;
        };
    }

    /**
     * Create a function for creating an instance of a class
     * @param string $className The class name to create
     * @param array $params (optional) An array of arguments to be passed to the constructor.
     * @return Closure Returns the anonymous function that clones the object
     * @since 1.0.0
     */
    public function instance($className, $params = array())
    {
        return function ($container) use ($className, $params) {
            return $container->instantiate($className, $params);
        };
    }

    /**
     * Create an instance of a class
     * @param string $className The class name to create
     * @param array $params (optional) An array of arguments to be passed to the constructor.
     * @return object Returns the instance of the class created.
     * @since 1.2.0
     */
    public function instantiate($className, $params = array())
    {
        $class = new \ReflectionClass($className);
        $constructor = $class->getConstructor();
        $result = null;
        if ($constructor) {
            $arguments = Container::buildDependencies($this, $constructor, $params);
            $result = $class->newInstanceArgs($arguments);
        } else {
            $result = $class->newInstance();
        }
        if ($result instanceof ConsumerInterface) {
            $result($this);
        }
        return $result;
    }

    /**
     * Create a function for storing an alias
     * @param string $name The name of the concrete IoC key
     * @return Closure Returns the anonymous function that stores the alias
     * @since 1.2.0
     */
    public function alias($name)
    {
        return function ($container) use ($name) {
            return $container[$name];
        };
    }

    /**
     * Build and load an array of dependencies from the constructor
     * @param Packfire\FuelBlade\ContainerInterface $container The container to get the values from
     * @param ReflectionMethod $constructor The reflection of the class constructor
     * @param array $params (optional) An array of arguments to be passed to the constructor.
     * @return array Returns an array of arguments that fit the constructor's parameters list.
     * @since 1.1.2
     */
    public static function buildDependencies(ContainerInterface $container, \ReflectionMethod $constructor, $params = array())
    {
        $parameters = $constructor->getParameters();
        $args = array();
        foreach ($parameters as $parameter) {
            $value = null;
            if (isset($params[$parameter->getName()])) {
                $value = $params[$parameter->getName()];
            } else {
                $class = $parameter->getClass();
                if ($class && isset($container[$class->name])) {
                        $value = $container[$class->name];
                } elseif ($parameter->isDefaultValueAvailable()) {
                    $value = $parameter->getDefaultValue();
                } elseif ($class) {
                    throw new \RuntimeException('Unable to find and build dependency "' . $class->name . '" for "' . $constructor->getDeclaringClass()->name . '::__construct()".');
                } else {
                    throw new \RuntimeException('Unable to build required constructor parameter "$' . $parameter->name . '" for ' . $constructor->getDeclaringClass()->name . '.');
                }
            }
            $args[] = $value;
        }
        return $args;
    }

    /**
     * Create a function that stores a shared value
     * @param mixed $callable The closure or object to be shared
     * @return Closure Returns the anonymous function that encapsulates the creation process
     * @since 1.0.0
     */
    public function share($callable)
    {
        return function ($c) use ($callable) {
            static $object = null;

            if (null === $object) {
                $object = $callable($c);
            }

            return $object;
        };
    }

    /**
     * Get a value from the container
     * @param string $id The key of the value
     * @return mixed Returns the value
     * @since 1.0.0
     */
    public function value($id)
    {
        return $this->values[$id];
    }
}
