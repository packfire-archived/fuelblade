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

use \RuntimeException;

/**
 * An exception occuring with the service loading
 *
 * @author Sam-Mauris Yong <sam@mauris.sg>
 * @copyright Sam-Mauris Yong <sam@mauris.sg>
 * @license http://www.opensource.org/licenses/BSD-3-Clause The BSD 3-Clause License
 * @package Packfire\FuelBlade
 * @since 1.1.1
 */
class ServiceLoadingException extends RuntimeException
{
    public function __construct($key)
    {
        parent::__construct(
            'The service "' . $key . '" defined in the service configuration'
            . ' does not contain a proper definition.'
        );
    }
}
