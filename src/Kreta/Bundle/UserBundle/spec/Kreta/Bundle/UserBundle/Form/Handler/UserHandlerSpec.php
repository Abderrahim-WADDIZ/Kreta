<?php

/**
 * This file belongs to Kreta.
 * The source code of application includes a LICENSE file
 * with all information about license.
 *
 * @author benatespina <benatespina@gmail.com>
 * @author gorkalaucirica <gorka.lauzirika@gmail.com>
 */

namespace spec\Kreta\Bundle\UserBundle\Form\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use Kreta\Bundle\CoreBundle\Event\FormHandlerEvent;
use Kreta\Component\Media\Factory\MediaFactory;
use Kreta\Component\Media\Model\Interfaces\MediaInterface;
use Kreta\Component\Media\Uploader\MediaUploader;
use Kreta\Component\Project\Model\Interfaces\ProjectInterface;
use Kreta\Component\User\Model\Interfaces\UserInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\FileBag;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class UserHandlerSpec.
 *
 * @package spec\Kreta\Bundle\UserBundle\Form\Handler
 */
class UserHandlerSpec extends ObjectBehavior
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
        $this->shouldHaveType('Kreta\Bundle\UserBundle\Form\Handler\UserHandler');
    }

    function it_extends_abstract_form_handler()
    {
        $this->shouldHaveType('Kreta\Bundle\CoreBundle\Form\Handler\Abstracts\AbstractHandler');
    }

    function it_handles_form(Request $request, ProjectInterface $project, FormFactory $formFactory, FormInterface $form)
    {
        $formFactory->create(Argument::type('Kreta\Bundle\UserBundle\Form\Type\UserType'), $project, [])
            ->shouldBeCalled()->willReturn($form);

        $this->handleForm($request, $project, [])->shouldReturn($form);
    }

    function it_handles_post_request(
        Request $request,
        UserInterface $user,
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
        $formFactory->create(Argument::type('Kreta\Bundle\UserBundle\Form\Type\UserType'), $user, [])
            ->shouldBeCalled()->willReturn($form);
        $request->isMethod('POST')->shouldBeCalled()->willReturn(true);
        $form->handleRequest($request)->shouldBeCalled();
        $form->isSubmitted()->shouldBeCalled()->willReturn(true);
        $form->isValid()->shouldBeCalled()->willReturn(true);

        $fileBag->get('kreta_user_user_type')->shouldBeCalled()->willReturn(["photo" => $image]);
        $request->files = $fileBag;

        $mediaFactory->create($image)->shouldBeCalled()->willReturn($media);
        $uploader->upload($media)->shouldBeCalled();
        $user->setPhoto($media)->shouldBeCalled()->willReturn($user);

        $manager->persist($user)->shouldBeCalled();
        $manager->flush()->shouldBeCalled();

        $eventDispatcher->dispatch(
            FormHandlerEvent::NAME, Argument::type('Kreta\Bundle\CoreBundle\Event\FormHandlerEvent')
        );

        $this->handleForm($request, $user, []);
    }
}
