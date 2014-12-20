<?php

/**
 * This file belongs to Kreta.
 * The source code of application includes a LICENSE file
 * with all information about license.
 *
 * @author benatespina <benatespina@gmail.com>
 * @author gorkalaucirica <gorka.lauzirika@gmail.com>
 */

namespace spec\Kreta\Component\Workflow\Model;

use Kreta\Component\Workflow\Model\Interfaces\WorkflowInterface;
use PhpSpec\ObjectBehavior;

/**
 * Class StatusSpec.
 *
 * @package spec\Kreta\Component\Core\Model
 */
class StatusSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('Open');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Kreta\Component\Workflow\Model\Status');
    }

    function it_extends_finite_state()
    {
        $this->shouldHaveType('Finite\State\State');
    }

    function it_implements_status_interface()
    {
        $this->shouldImplement('Kreta\Component\Workflow\Model\Interfaces\StatusInterface');
    }

    function it_should_not_have_id_by_default()
    {
        $this->getId()->shouldReturn(null);
    }

    function its_id_is_mutable()
    {
        $this->setId('status-id')->shouldReturn($this);
        $this->getId()->shouldReturn('status-id');
    }

    function its_color_is_mutable()
    {
        $this->setColor('#FFFFFF')->shouldReturn($this);
        $this->getColor()->shouldReturn('#FFFFFF');
    }

    function its_name_is_mutable()
    {
        $this->getName()->shouldReturn('Open');

        $this->setName('Closed')->shouldReturn($this);
        $this->getName()->shouldReturn('Closed');
    }

    function its_type_is_mutable()
    {
        $this->getType()->shouldReturn('normal');

        $this->setType('initial')->shouldReturn($this);
        $this->getType()->shouldReturn('initial');
    }

    function its_workflow_is_mutable(WorkflowInterface $workflow)
    {
        $this->setWorkflow($workflow)->shouldReturn($this);
        $this->getWorkflow()->shouldReturn($workflow);
    }

    function its_to_string_returns_id()
    {
        $this->__toString(null);

        $this->setName('Done')->shouldReturn($this);
        $this->__toString('Done');
    }
}
