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

namespace spec\Kreta\Component\User\Factory;

use PhpSpec\ObjectBehavior;

/**
 * Class UserFactorySpec.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class UserFactorySpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('Kreta\Component\User\Model\User');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Kreta\Component\User\Factory\UserFactory');
    }

    function it_creates_a_user()
    {
        $this->create('kreta@kreta.com', 'user', 'First Name', 'Last Name', false)
            ->shouldReturnAnInstanceOf('Kreta\Component\User\Model\Interfaces\UserInterface');
    }
}
