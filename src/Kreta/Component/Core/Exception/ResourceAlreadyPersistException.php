<?php

/*
 * This file is part of the Kreta package.
 *
 * (c) Beñat Espiña <benatespina@gmail.com>
 * (c) Gorka Laucirica <gorka.lauzirika@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kreta\Component\Core\Exception;

/**
 * Class ResourceAlreadyPersistException.
 *
 * @package Kreta\Component\Core\Exception
 */
class ResourceAlreadyPersistException extends \Exception
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct('The resource is already persisted');
    }
}
