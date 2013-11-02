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

/**
 * Interfacing of a service consumer
 * 
 * @author Sam-Mauris Yong <sam@mauris.sg>
 * @copyright Sam-Mauris Yong <sam@mauris.sg>
 * @license http://www.opensource.org/licenses/BSD-3-Clause The BSD 3-Clause License
 * @package Packfire\FuelBlade
 * @since 1.0.0
 */
interface ConsumerInterface
{
    /**
     * Make the object invokable as a function
     * @param \Packfire\FuelBlade\Container $container The container that the consumer is called from
     * @since 1.0.0
     */
    public function __invoke($container);
}
