<?php

/**
 * This file belongs to Kreta.
 * The source code of application includes a LICENSE file
 * with all information about license.
 *
 * @author benatespina <benatespina@gmail.com>
 * @author gorkalaucirica <gorka.lauzirika@gmail.com>
 */

namespace Kreta\Component\Core\Factory;

use Kreta\Component\Core\Factory\Abstracts\AbstractFactory;
use Kreta\Component\Core\Model\ProjectRole;

/**
 * Class ProjectRoleFactory.
 *
 * @package Kreta\Component\Core\Factory
 */
class ProjectRoleFactory extends AbstractFactory
{
    /**
     * {@inheritdoc}
     */
    public function create()
    {
        return new ProjectRole();
    }
}
