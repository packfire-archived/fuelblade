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

class Container implements ContainerInterface, \ArrayAccess {
    
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
    public function offsetExists($id) {
        return isset($this->values[$id]);
    }

    /**
     * Provides implementation of offsetGet in \ArrayAccess
     * @internal
     * @param string $id
     * @return mixed
     * @since 1.0.0
     */
    public function offsetGet($id) {
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
    protected function loadValue($value){
        if(is_object($value) && method_exists($value, '__invoke')){
            return $value($this);
        }else{
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
    public function offsetSet($id, $value) {
        $this->values[$id] = $value;
    }

    /**
     * Provides implementation of offsetUnset in \ArrayAccess
     * @internal
     * @param string $id
     * @since 1.0.0
     */
    public function offsetUnset($id) {
        unset($this->values[$id]);
    }

    /**
     * Create a function for cloning an object
     * @param object $object The object to be cloned when the function is called
     * @return Closure Returns the anonymous function that clones the object
     * @since 1.0.0
     */
    public function copy($object) {
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
    public function func(Closure $callable) {
        return function()use(&$callable){
            return $callable;
        };
    }
    
    /**
     * Create a function for creating an instance of a class
     * @param string $class The class name to create
     * @return Closure Returns the anonymous function that clones the object
     * @since 1.0.0
     */
    public function instance($class) {
        return function()use($class){
            return new $class();
        };
    }
    
    /**
     * Create a function that stores a shared value
     * @param Closure $callable The closure that creates the value to be shared
     * @return Closure Returns the anonymous function that encapsulates the creation process
     * @since 1.0.0
     */
    public function share(Closure $callable) {
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
    public function value($id){
        return $this->values[$id];
    }
    
}