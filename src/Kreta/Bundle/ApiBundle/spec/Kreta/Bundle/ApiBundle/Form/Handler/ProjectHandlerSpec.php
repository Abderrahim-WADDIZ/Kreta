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
use Kreta\Bundle\WebBundle\Event\FormHandlerEvent;
use Kreta\Component\Media\Factory\MediaFactory;
use Kreta\Component\Media\Model\Interfaces\MediaInterface;
use Kreta\Component\Project\Model\Interfaces\ProjectInterface;
use Kreta\Component\Media\Uploader\MediaUploader;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\FileBag;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ProjectHandlerSpec.
 *
 * @package spec\Kreta\Bundle\ApiBundle\Form\Handler
 */
class ProjectHandlerSpec extends ObjectBehavior
{
    function let(
        FormFactory $formFactory,
        ObjectManager $manager,
        EventDispatcherInterface $eventDispatcher,
        MediaFactory $mediaFactory,
        MediaUploader $uploader
    )
    {
        $this->beConstructedWith($formFactory, $manager, $eventDispatcher, $mediaFactory, $uploader);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Kreta\Bundle\ApiBundle\Form\Handler\ProjectHandler');
    }

    function it_extends_core_issue_handler()
    {
        $this->shouldHaveType('Kreta\Bundle\WebBundle\FormHandler\ProjectFormHandler');
    }

    function it_handles_form(Request $request, ProjectInterface $project, FormFactory $formFactory, FormInterface $form)
    {
        $formFactory->create(Argument::type('\Kreta\Bundle\ApiBundle\Form\Type\ProjectType'), $project, [])
            ->shouldBeCalled()->willReturn($form);

        $this->handleForm($request, $project, [])->shouldReturn($form);
    }

    function it_handles_post_request(
        Request $request,
        ProjectInterface $project,
        FormFactory $formFactory,
        FormInterface $form,
        FileBag $fileBag,
        MediaFactory $mediaFactory,
        MediaInterface $media,
        ObjectManager $manager,
        MediaUploader $uploader,
        EventDispatcherInterface $eventDispatcher
    )
    {
        $image = new UploadedFile('', '', null, null, 99, true); //Avoids file not found exception
        $formFactory->create(Argument::type('\Kreta\Bundle\ApiBundle\Form\Type\ProjectType'), $project, [])
            ->shouldBeCalled()->willReturn($form);
        $request->isMethod('POST')->shouldBeCalled()->willReturn(true);
        $form->handleRequest($request)->shouldBeCalled();
        $form->isSubmitted()->shouldBeCalled()->willReturn(true);
        $form->isValid()->shouldBeCalled()->willReturn(true);

        $fileBag->get('image')->shouldBeCalled()->willReturn($image);
        $request->files = $fileBag;

        $mediaFactory->create($image)->shouldBeCalled()->willReturn($media);
        $uploader->upload($media)->shouldBeCalled();
        $project->setImage($media)->shouldBeCalled()->willReturn($project);

        $manager->persist($project)->shouldBeCalled();
        $manager->flush()->shouldBeCalled();

        $eventDispatcher->dispatch(
            FormHandlerEvent::NAME, Argument::type('\Kreta\Bundle\WebBundle\Event\FormHandlerEvent')
        );

        $this->handleForm($request, $project, []);
    }
}
