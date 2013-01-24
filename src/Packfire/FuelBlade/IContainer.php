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

interface IContainer {
    
    public function instance($class);
    
    public function share(Closure $callable);
    
    public function func(Closure $callable);
    
    public function copy($object);
    
    public function value($key);
    
}