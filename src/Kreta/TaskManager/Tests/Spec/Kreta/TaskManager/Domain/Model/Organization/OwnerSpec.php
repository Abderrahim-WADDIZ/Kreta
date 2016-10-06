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

namespace Spec\Kreta\TaskManager\Domain\Model\Organization;

use Kreta\TaskManager\Domain\Model\Organization\Owner;
use Kreta\TaskManager\Domain\Model\Organization\OwnerId;
use Kreta\TaskManager\Domain\Model\Organization\Participant;
use PhpSpec\ObjectBehavior;

class OwnerSpec extends ObjectBehavior
{
    function let(OwnerId $ownerId)
    {
        $ownerId->id()->willReturn('owner-id');
        $this->beConstructedWith($ownerId);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Owner::class);
    }

    function it_extends_participant()
    {
        $this->shouldHaveType(Participant::class);
    }

    function it_gets_id()
    {
        $this->id()->shouldReturnAnInstanceOf(OwnerId::class);
        $this->__toString()->shouldReturn('owner-id');
    }
}
