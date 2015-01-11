<?php

/**
 * This file belongs to Kreta.
 * The source code of application includes a LICENSE file
 * with all information about license.
 *
 * @author benatespina <benatespina@gmail.com>
 * @author gorkalaucirica <gorka.lauzirika@gmail.com>
 */

namespace spec\Kreta\Bundle\ApiBundle\Form\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use Kreta\Component\Issue\Factory\IssueFactory;
use Kreta\Component\Issue\Model\Interfaces\IssueInterface;
use Kreta\Component\Project\Model\Interfaces\ParticipantInterface;
use Kreta\Component\Project\Model\Interfaces\ProjectInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\DependencyInjection\Exception\ParameterNotFoundException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContextInterface;

/**
 * Class IssueHandlerSpec.
 *
 * @package spec\Kreta\Bundle\ApiBundle\Form\Handler
 */
class IssueHandlerSpec extends ObjectBehavior
{
    function let(
        FormFactory $formFactory,
        ObjectManager $manager,
        EventDispatcherInterface $eventDispatcher,
        SecurityContextInterface $context,
        IssueFactory $issueFactory
    )
    {
        $this->beConstructedWith($formFactory, $manager, $eventDispatcher, $context, $issueFactory);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Kreta\Bundle\ApiBundle\Form\Handler\IssueHandler');
    }

    function it_extends_core_issue_handler()
    {
        $this->shouldHaveType('Kreta\Bundle\IssueBundle\Form\Handler\IssueHandler');
    }

    function it_does_not_handle_form_because_project_key_does_not_exist(IssueInterface $issue, Request $request)
    {
        $this->shouldThrow(new ParameterNotFoundException('project'))
            ->during('handleForm', [$request, $issue, []]);
    }

    function it_handles_form(
        Request $request,
        IssueInterface $issue,
        FormFactory $formFactory,
        FormInterface $form,
        ProjectInterface $project,
        ParticipantInterface $participant
    )
    {
        $project->getParticipants()->shouldBeCalled()->willReturn([$participant]);
        $formFactory->create(Argument::type('\Kreta\Bundle\ApiBundle\Form\Type\IssueType'), $issue, [])
            ->shouldBeCalled()->willReturn($form);

        $this->handleForm($request, $issue, ['project' => $project])->shouldReturn($form);
    }
}
