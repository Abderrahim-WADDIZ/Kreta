<?php

/**
 * This file belongs to Kreta.
 * The source code of application includes a LICENSE file
 * with all information about license.
 *
 * @author benatespina <benatespina@gmail.com>
 * @author gorkalaucirica <gorka.lauzirika@gmail.com>
 */

namespace spec\Kreta\Bundle\ApiBundle\Controller;

use FOS\RestBundle\Request\ParamFetcher;
use Kreta\Bundle\ApiBundle\Form\Handler\WorkflowHandler;
use Kreta\Bundle\ApiBundle\spec\Kreta\Bundle\ApiBundle\Controller\BaseRestController;
use Kreta\Component\User\Model\Interfaces\UserInterface;
use Kreta\Component\Workflow\Model\Interfaces\WorkflowInterface;
use Kreta\Component\Workflow\Repository\WorkflowRepository;
use Prophecy\Argument;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\SecurityContextInterface;

/**
 * Class WorkflowControllerSpec.
 *
 * @package spec\Kreta\Bundle\ApiBundle\Controller
 */
class WorkflowControllerSpec extends BaseRestController
{
    function let(ContainerInterface $container)
    {
        $this->setContainer($container);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Kreta\Bundle\ApiBundle\Controller\WorkflowController');
    }

    function it_extends_rest_controller()
    {
        $this->shouldHaveType('Kreta\Bundle\ApiBundle\Controller\RestController');
    }

    function it_gets_workflows(
        ContainerInterface $container,
        WorkflowRepository $workflowRepository,
        SecurityContextInterface $context,
        TokenInterface $token,
        UserInterface $user,
        ParamFetcher $paramFetcher,
        WorkflowInterface $workflow
    )
    {
        $container->get('kreta_workflow.repository.workflow')->shouldBeCalled()->willReturn($workflowRepository);
        $user = $this->getUserSpec($container, $context, $token, $user);
        $paramFetcher->get('sort')->shouldBeCalled()->willReturn('createdAt');
        $paramFetcher->get('limit')->shouldBeCalled()->willReturn(10);
        $paramFetcher->get('offset')->shouldBeCalled()->willReturn(1);
        $workflowRepository->findBy(['creator' => $user], ['createdAt' => 'ASC'], 10, 1)
            ->shouldBeCalled()->willReturn([$workflow]);

        $this->getWorkflowsAction($paramFetcher)->shouldReturn([$workflow]);
    }

    function it_does_not_get_workflow_because_the_user_has_not_the_required_grant(
        ContainerInterface $container,
        WorkflowRepository $workflowRepository,
        WorkflowInterface $workflow,
        SecurityContextInterface $context
    )
    {
        $this->getWorkflowIfAllowedSpec($container, $workflowRepository, $workflow, $context, 'view', false);

        $this->shouldThrow(new AccessDeniedException())->during('getWorkflowAction', ['workflow-id']);
    }

    function it_gets_workflow(
        ContainerInterface $container,
        WorkflowRepository $workflowRepository,
        WorkflowInterface $workflow,
        SecurityContextInterface $context
    )
    {
        $workflow = $this->getWorkflowIfAllowedSpec($container, $workflowRepository, $workflow, $context);

        $this->getWorkflowAction('workflow-id')->shouldReturn($workflow);
    }

    function it_posts_workflow(
        ContainerInterface $container,
        WorkflowHandler $workflowHandler,
        Request $request,
        WorkflowInterface $workflow
    )
    {
        $container->get('kreta_api.form_handler.workflow')->shouldBeCalled()->willReturn($workflowHandler);
        $container->get('request')->shouldBeCalled()->willReturn($request);
        $workflowHandler->processForm($request)->shouldBeCalled()->willReturn($workflow);

        $this->postWorkflowAction()->shouldReturn($workflow);
    }

    function it_does_not_put_workflow_because_the_user_has_not_the_required_grant(
        ContainerInterface $container,
        WorkflowHandler $workflowHandler,
        Request $request,
        WorkflowRepository $workflowRepository,
        WorkflowInterface $workflow,
        SecurityContextInterface $context
    )
    {
        $container->get('kreta_api.form_handler.workflow')->shouldBeCalled()->willReturn($workflowHandler);
        $container->get('request')->shouldBeCalled()->willReturn($request);
        $this->getWorkflowIfAllowedSpec($container, $workflowRepository, $workflow, $context, 'edit', false);

        $this->shouldThrow(new AccessDeniedException())->during('putWorkflowAction', ['workflow-id']);
    }

    function it_puts_workflow(
        ContainerInterface $container,
        WorkflowHandler $workflowHandler,
        Request $request,
        WorkflowRepository $workflowRepository,
        WorkflowInterface $workflow,
        SecurityContextInterface $context
    )
    {
        $container->get('kreta_api.form_handler.workflow')->shouldBeCalled()->willReturn($workflowHandler);
        $container->get('request')->shouldBeCalled()->willReturn($request);
        $workflow = $this->getWorkflowIfAllowedSpec($container, $workflowRepository, $workflow, $context, 'edit');
        $workflowHandler->processForm($request, $workflow, ['method' => 'PUT'])
            ->shouldBeCalled()->willReturn($workflow);

        $this->putWorkflowAction('workflow-id')->shouldReturn($workflow);
    }
}
