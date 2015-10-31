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

namespace spec\Kreta\Component\Core\Exception;

use PhpSpec\ObjectBehavior;

/**
 * Class CollectionMinLengthExceptionSpec.
 *
 * @package spec\Kreta\Component\Core\Exception
 */
class CollectionMinLengthExceptionSpec extends ObjectBehavior 
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Kreta\Component\Core\Exception\CollectionMinLengthException');
    }

    function it_extends_exception()
    {
        $this->shouldHaveType('Exception');
    }
}
