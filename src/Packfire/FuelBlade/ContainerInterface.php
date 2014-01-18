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
 * Interfacing of a FuelBlade IoC container
 *
 * @author Sam-Mauris Yong <sam@mauris.sg>
 * @copyright Sam-Mauris Yong <sam@mauris.sg>
 * @license http://www.opensource.org/licenses/BSD-3-Clause The BSD 3-Clause License
 * @package Packfire\FuelBlade
 * @since 1.0.0
 */
interface ContainerInterface
{
    /**
     * Create a function for creating an instance of a class
     * @param string $class The class name to create
     * @param array $params (optional) An array of arguments to be passed to the constructor.
     * @return Closure Returns the anonymous function that clones the object
     * @since 1.0.0
     */
    public function instance($class, $params = array());

    /**
     * Create an instance of a class
     * @param string $className The class name to create
     * @param array $params (optional) An array of arguments to be passed to the constructor.
     * @return object Returns the instance of the class created.
     * @since 1.2.0
     */
    public function instantiate($class, $params = array());

    /**
     * Create a function that stores a shared value
     * @param Closure $callable The closure that creates the value to be shared
     * @return Closure Returns the anonymous function that encapsulates the creation process
     * @since 1.0.0
     */
    public function share($callable);

    /**
     * Create a function for storing a Closure
     * @param Closure The closure to be stored.
     * @return Closure Returns the anonymous function that stores the original Closure
     * @since 1.0.0
     */
    public function func(Closure $callable);

    /**
     * Create a function for cloning an object
     * @param object $object The object to be cloned when the function is called
     * @return Closure Returns the anonymous function that clones the object
     * @since 1.0.0
     */
    public function copy($object);

    /**
     * Create a function for storing an alias
     * @param string $name The name of the concrete IoC key
     * @return Closure Returns the anonymous function that stores the alias
     * @since 2.0.0
     */
    public function alias($name);

    /**
     * Get a value from the container
     * @param string $id The key of the value
     * @return mixed Returns the value
     * @since 1.0.0
     */
    public function value($key);
}
