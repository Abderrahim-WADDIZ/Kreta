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

declare(strict_types=1);

namespace Kreta\IdentityAccess\Infrastructure\Symfony\HttpAction;

use BenGorUser\SimpleBusBridge\CommandBus\SimpleBusUserCommandBus;
use BenGorUser\User\Domain\Model\Exception\UserDoesNotExistException;
use BenGorUser\User\Domain\Model\Exception\UserInvitationAlreadyAcceptedException;
use Kreta\IdentityAccess\Infrastructure\Symfony\Form\FormErrorSerializer;
use Kreta\IdentityAccess\Infrastructure\Symfony\Form\Type\ResendInvitationType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ResendInvitationAction
{
    private $formFactory;
    private $commandBus;

    public function __construct(FormFactoryInterface $formFactory, SimpleBusUserCommandBus $commandBus)
    {
        $this->formFactory = $formFactory;
        $this->commandBus = $commandBus;
    }

    public function __invoke(Request $request)
    {
        $form = $this->formFactory
            ->createNamedBuilder('', ResendInvitationType::class)
            ->getForm();

        $form->handleRequest($request);
        if ($form->isValid()) {
            try {
                $this->commandBus->handle($form->getData());

                return new JsonResponse([
                    'email' => $form->getData()->email(),
                ]);
            } catch (UserDoesNotExistException $exception) {
                return new JsonResponse(
                    sprintf(
                        'The %s email is does not exist',
                        $form->getData()->email()
                    ),
                    404
                );
            } catch (UserInvitationAlreadyAcceptedException $exception) {
                return new JsonResponse(
                    sprintf(
                        'The %s email is already accepted invitation',
                        $form->getData()->email()
                    ),
                    409
                );
            }
        }

        return new JsonResponse(FormErrorSerializer::errors($form), 400);
    }
}
