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

class Container implements IContainer, \ArrayAccess {
    
    protected $values = array();
    
    public function offsetExists($id) {
        return isset($this->values[$id]);
    }

    public function offsetGet($id) {
        if (!isset($this->values[$id])) {
            throw new InvalidArgumentException(sprintf('"%s" is not defined in FuelBlade IoC container.', $id));
        }
        
        $value = $this->values[$id];
        return $this->loadValue($value);
    }
    
    protected function loadValue($value){
        if($value instanceof Closure){
            return Closure::bind($value, $this, __CLASS__);
        }elseif($value instanceof IConsumer){
            return $value($this);
        }else{
            return $value;
        }
    }

    public function offsetSet($id, $value) {
        $this->values[$id] = $value;
    }

    public function offsetUnset($id) {
        unset($this->values[$id]);
    }
    
    public function copy($object) {
        return function () use ($object) {
            return clone $object;
        };
    }

    public function func(Closure $callable) {
        return function()use(&$callable){
            return $callable;
        };
    }

    public function instance($class) {
        return function()use(&$class){
            return new $class();
        };
    }

    public function share(Closure $callable) {
        return function () use ($callable) {
            static $object = null;

            if (null === $object) {
                $object = $this->loadValue($callable);
            }

            return $object;
        };
    }
    
    public function value($id){
        return $this->values[$id];
    }
    
}