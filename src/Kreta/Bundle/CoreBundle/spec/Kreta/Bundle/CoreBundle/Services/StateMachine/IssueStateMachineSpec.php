<?php

/**
 * This file belongs to Kreta.
 * The source code of application includes a LICENSE file
 * with all information about license.
 *
 * @author benatespina <benatespina@gmail.com>
 * @author gorkalaucirica <gorka.lauzirika@gmail.com>
 */

namespace spec\Kreta\Bundle\CoreBundle\Services\StateMachine;

use Kreta\Component\Core\Model\Interfaces\IssueInterface;
use Kreta\Component\Core\Model\Interfaces\StatusInterface;
use Kreta\Component\Core\Model\Interfaces\StatusTransitionInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class IssueStateMachineSpec.
 *
 * @package spec\Kreta\Bundle\CoreBundle\Services\StateMachine
 */
class IssueStateMachineSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Kreta\Bundle\CoreBundle\Services\StateMachine\IssueStateMachine');
    }

    function it_extends_state_machine()
    {
        $this->shouldHaveType('Finite\StateMachine\StateMachine');
    }

    function it_loads(IssueInterface $issue, StatusInterface $status, StatusInterface $status2,
                      StatusTransitionInterface $transition)
    {
        $status->getName()->shouldBeCalled()->willReturn('Open');
        $status->getType()->shouldBeCalled()->willReturn('initial');

        $status2->getName()->shouldBeCalled()->willReturn('In progress');
        $status2->getType()->shouldBeCalled()->willReturn('normal');

        $transition->getInitialStates()->shouldBeCalled()->willReturn(array($status));
        $transition->getName()->shouldBeCalled()->willReturn('Start progress');
        $transition->getState()->shouldBeCalled()->willReturn($status2);

        $this->load($issue, array($status, $status2), array($transition))
            ->shouldReturnAnInstanceOf('Kreta\Bundle\CoreBundle\Services\StateMachine\IssueStateMachine');
    }
}
