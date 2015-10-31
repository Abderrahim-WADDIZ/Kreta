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

namespace Kreta\Component\Notification\NotifiableEvent\Registry;

/**
 * Class NonExistingNotifierException.
 *
 * @package Kreta\Component\Notification\Notifier\Registry
 */
class ExistingNotifiableEventException extends \InvalidArgumentException
{
    /**
     * Constructor.
     *
     * @param string $name The name
     */
    public function __construct($name)
    {
        parent::__construct(sprintf('Notifiable event with name "%s" already exists', $name));
    }
}
