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
 * Loads configuration for a IoC
 *
 * @author Sam-Mauris Yong <sam@mauris.sg>
 * @copyright Sam-Mauris Yong <sam@mauris.sg>
 * @license http://www.opensource.org/licenses/BSD-3-Clause The BSD 3-Clause License
 * @package Packfire\FuelBlade
 * @since 1.1.1
 */
class ServiceLoader
{
    /**
     * Perfom loading of services from a configuration into a container
     * @param Packfire\FuelBlade\ContainerInterface $container The container to be loaded with the services
     * @param array The collection of services to be loaded
     * @since 1.1.1
     */
    public static function load(ContainerInterface $container, array $services)
    {
        foreach ($services as $key => $service) {
            if (isset($service['class'])) {
                $package = $service['class'];
                $params = isset($service['parameters']) ? $service['parameters'] : null;
                $container[$key] = $container->share(
                    function ($c) use ($package, $params) {
                        if (class_exists($package)) {
                            $reflect = new \ReflectionClass($package);
                            if ($params) {
                                foreach ($params as &$param) {
                                    $match = array();
                                    if (is_string($param) && preg_match('/^@{1}([^@].+?)$/', $param, $match)) {
                                        if (isset($c[$match[1]])) {
                                            $param = $c[$match[1]];
                                        }
                                    }
                                }
                                $instance = $reflect->newInstanceArgs($params);
                            } else {
                                $instance = $reflect->newInstance();
                            }
                            if ($instance instanceof ConsumerInterface) {
                                return $instance($c);
                            }
                            return $instance;
                        }
                    }
                );
            } else {
                throw new ServiceLoadingException($key);
            }
        }
    }
}
