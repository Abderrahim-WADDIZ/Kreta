<?php

/**
 * This file belongs to Kreta.
 * The source code of application includes a LICENSE file
 * with all information about license.
 *
 * @author benatespina <benatespina@gmail.com>
 * @author gorkalaucirica <gorka.lauzirika@gmail.com>
 */

namespace spec\Kreta\Bundle\Api\ApiCoreBundle\Controller;

use Doctrine\Common\Persistence\AbstractManagerRegistry;
use Doctrine\Common\Persistence\ObjectManager;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\View\ViewHandler;
use Kreta\Bundle\Api\ApiCoreBundle\Form\Type\ProjectType;
use Kreta\Component\Core\Factory\ParticipantFactory;
use Kreta\Component\Core\Factory\ProjectFactory;
use Kreta\Component\Core\Model\Interfaces\ParticipantInterface;
use Kreta\Component\Core\Model\Interfaces\ProjectInterface;
use Kreta\Component\Core\Model\Interfaces\UserInterface;
use Kreta\Component\Core\Repository\ProjectRepository;
use Prophecy\Argument;
use spec\Kreta\Bundle\Api\ApiCoreBundle\Controller\Abstracts\AbstractRestControllerSpec;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\SecurityContextInterface;

/**
 * Class ProjectControllerSpec.
 *
 * @package spec\Kreta\Bundle\Api\ApiCoreBundle\Controller
 */
class ProjectControllerSpec extends AbstractRestControllerSpec
{
    function let(ContainerInterface $container)
    {
        $this->setContainer($container);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Kreta\Bundle\Api\ApiCoreBundle\Controller\ProjectController');
    }

    function it_extends_abstract_rest_controller()
    {
        $this->shouldHaveType('Kreta\Bundle\Api\ApiCoreBundle\Controller\Abstracts\AbstractRestController');
    }

    function it_does_not_get_projects_because_the_user_is_not_logged(
        ContainerInterface $container,
        SecurityContextInterface $securityContext,
        TokenInterface $token,
        ParamFetcher $paramFetcher
    )
    {
        $this->getCurrentUser($container, $securityContext, $token);

        $this->shouldThrow(new AccessDeniedException('Not allowed to access this resource'))
            ->during('getProjectsAction', [$paramFetcher]);
    }

    function it_gets_projects(
        ContainerInterface $container,
        ProjectRepository $projectRepository,
        ParamFetcher $paramFetcher,
        ViewHandler $viewHandler,
        SecurityContextInterface $securityContext,
        TokenInterface $token,
        UserInterface $user,
        Response $response
    )
    {
        $container->has('security.context')->shouldBeCalled()->willReturn(true);
        $container->get('security.context')->shouldBeCalled()->willReturn($securityContext);
        $securityContext->getToken()->shouldBeCalled()->willReturn($token);
        $token->getUser()->shouldBeCalled()->willReturn($user);

        $container->get('kreta_core.repository_project')
            ->shouldBeCalled()->willReturn($projectRepository);
        $paramFetcher->get('order')->shouldBeCalled()->willReturn('name');
        $paramFetcher->get('count')->shouldBeCalled()->willReturn(10);
        $paramFetcher->get('page')->shouldBeCalled()->willReturn(1);
        $projectRepository->findByParticipant($user, 'name', 10, 1)->shouldBeCalled()->willReturn([]);

        $container->get('fos_rest.view_handler')->shouldBeCalled()->willReturn($viewHandler);
        $viewHandler->handle(Argument::type('FOS\RestBundle\View\View'))->shouldBeCalled()->willReturn($response);

        $this->getProjectsAction($paramFetcher)->shouldReturn($response);
    }

    function it_does_not_get_project_because_the_project_does_not_exist(
        ContainerInterface $container,
        ProjectRepository $projectRepository
    )
    {
        $this->getProjectIfExist($container, $projectRepository);

        $this->shouldThrow(new NotFoundHttpException('Does not exist any entity with project-id id'))
            ->during('getProjectAction', ['project-id']);
    }

    function it_does_not_get_project_because_the_user_has_not_the_required_grant(
        ContainerInterface $container,
        ProjectRepository $projectRepository,
        ProjectInterface $project,
        SecurityContextInterface $securityContext
    )
    {
        $this->getProjectIfAllowed($container, $projectRepository, $project, $securityContext, 'view', false);

        $this->shouldThrow(new AccessDeniedException('Not allowed to access this resource'))
            ->during('getProjectAction', ['project-id']);
    }

    function it_gets_project(
        ContainerInterface $container,
        ProjectRepository $projectRepository,
        ProjectInterface $project,
        SecurityContextInterface $securityContext,
        ViewHandler $viewHandler,
        Response $response
    )
    {
        $this->getProjectIfAllowed($container, $projectRepository, $project, $securityContext);

        $container->get('fos_rest.view_handler')->shouldBeCalled()->willReturn($viewHandler);
        $viewHandler->handle(Argument::type('FOS\RestBundle\View\View'))->shouldBeCalled()->willReturn($response);

        $this->getProjectAction('project-id')->shouldReturn($response);
    }

    function it_posts_project(
        ContainerInterface $container,
        ProjectFactory $projectFactory,
        ProjectInterface $project,
        ParticipantFactory $participantFactory,
        ParticipantInterface $participant,
        SecurityContextInterface $securityContext,
        TokenInterface $token,
        UserInterface $user,
        Request $request,
        FormFactoryInterface $formFactory,
        FormInterface $form,
        AbstractManagerRegistry $registry,
        ObjectManager $manager,
        ViewHandler $viewHandler,
        Response $response
    )
    {
        $this->getCurrentUser($container, $securityContext, $token, $user);

        $container->get('kreta_core.factory_project')->shouldBeCalled()->willReturn($projectFactory);
        $projectFactory->create($user)->shouldBeCalled()->willReturn($project);


        $this->processForm(
            $container,
            $request,
            $formFactory,
            $form,
            $registry,
            $manager,
            $viewHandler,
            $response,
            new ProjectType(),
            $project
        );

        $container->get('fos_rest.view_handler')->shouldBeCalled()->willReturn($viewHandler);
        $viewHandler->handle(Argument::type('FOS\RestBundle\View\View'))->shouldBeCalled()->willReturn($response);

        $this->postProjectsAction()->shouldReturn($response);
    }

    function it_does_not_posts_project_because_there_are_some_form_errors(
        ContainerInterface $container,
        ProjectFactory $projectFactory,
        ProjectInterface $project,
        ParticipantFactory $participantFactory,
        ParticipantInterface $participant,
        SecurityContextInterface $securityContext,
        TokenInterface $token,
        UserInterface $user,
        Request $request,
        FormFactoryInterface $formFactory,
        FormInterface $form,
        FormError $error,
        FormInterface $formChild,
        FormInterface $formGrandChild,
        ViewHandler $viewHandler,
        Response $response
    )
    {
        $container->get('kreta_core.factory_project')->shouldBeCalled()->willReturn($projectFactory);
        $this->getCurrentUser($container, $securityContext, $token, $user);
        $projectFactory->create($user)->shouldBeCalled()->willReturn($project);

        $this->getFormErrors(
            $container,
            $request,
            $formFactory,
            $form,
            $error,
            $formChild,
            $formGrandChild,
            $viewHandler,
            $response,
            new ProjectType(),
            $project
        );

        $container->get('fos_rest.view_handler')->shouldBeCalled()->willReturn($viewHandler);
        $viewHandler->handle(Argument::type('FOS\RestBundle\View\View'))->shouldBeCalled()->willReturn($response);

        $this->postProjectsAction()->shouldReturn($response);
    }

    function it_puts_project(
        ContainerInterface $container,
        ProjectRepository $projectRepository,
        ProjectInterface $project,
        SecurityContextInterface $securityContext,
        Request $request,
        FormFactoryInterface $formFactory,
        FormInterface $form,
        AbstractManagerRegistry $registry,
        ObjectManager $manager,
        ViewHandler $viewHandler,
        Response $response
    )
    {
        $this->getProjectIfAllowed($container, $projectRepository, $project, $securityContext, 'edit');

        $this->processForm(
            $container,
            $request,
            $formFactory,
            $form,
            $registry,
            $manager,
            $viewHandler,
            $response,
            new ProjectType(),
            $project,
            'PUT'
        );

        $container->get('fos_rest.view_handler')->shouldBeCalled()->willReturn($viewHandler);
        $viewHandler->handle(Argument::type('FOS\RestBundle\View\View'))->shouldBeCalled()->willReturn($response);

        $this->putProjectsAction('project-id')->shouldReturn($response);
    }

    function it_does_not_puts_project_because_there_are_some_form_errors(
        ContainerInterface $container,
        ProjectRepository $projectRepository,
        ProjectInterface $project,
        SecurityContextInterface $securityContext,
        Request $request,
        FormFactoryInterface $formFactory,
        FormInterface $form,
        FormError $error,
        FormInterface $formChild,
        FormInterface $formGrandChild,
        ViewHandler $viewHandler,
        Response $response
    )
    {
        $this->getProjectIfAllowed($container, $projectRepository, $project, $securityContext, 'edit');

        $this->getFormErrors(
            $container,
            $request,
            $formFactory,
            $form,
            $error,
            $formChild,
            $formGrandChild,
            $viewHandler,
            $response,
            new ProjectType(),
            $project,
            'PUT'
        );

        $container->get('fos_rest.view_handler')->shouldBeCalled()->willReturn($viewHandler);
        $viewHandler->handle(Argument::type('FOS\RestBundle\View\View'))->shouldBeCalled()->willReturn($response);

        $this->putProjectsAction('project-id')->shouldReturn($response);
    }
}
