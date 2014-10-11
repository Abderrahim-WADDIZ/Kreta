<?php

/**
 * This file belongs to Kreta.
 * The source code of application includes a LICENSE file
 * with all information about license.
 *
 * @author benatespina <benatespina@gmail.com>
 * @author gorkalaucirica <gorka.lauzirika@gmail.com>
 */

namespace Kreta\CoreBundle\Factory;

use Kreta\CoreBundle\Entity\Label;
use Kreta\CoreBundle\Factory\Abstracts\AbstractFactory;

/**
 * Class LabelFactory.
 *
 * @package Kreta\CoreBundle\Factory
 */
class LabelFactory extends AbstractFactory
{
    public function create()
    {
        return new Label();
    }
}
