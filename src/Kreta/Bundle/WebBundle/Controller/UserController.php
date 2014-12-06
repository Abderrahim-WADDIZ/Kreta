<?php

/**
 * This file belongs to Kreta.
 * The source code of application includes a LICENSE file
 * with all information about license.
 *
 * @author benatespina <benatespina@gmail.com>
 * @author gorkalaucirica <gorka.lauzirika@gmail.com>
 */

namespace Kreta\Bundle\WebBundle\Controller;

use Kreta\Bundle\CoreBundle\Form\Type\UserType;
use Kreta\Component\Core\Model\Interfaces\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Class UserController.
 *
 * @package Kreta\Bundle\WebBundle\Controller
 */
class UserController extends Controller
{
    /**
     * Edit action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request The request
     *
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request)
    {
        $user = $this->getUser();
        if (!$user instanceof UserInterface) {
            throw new AccessDeniedException();
        }

        $form = $this->get('kreta_web.form_handler_user')->handleForm($request, $user);

        return $this->render('@KretaWeb/User/edit.html.twig', ['form' => $form->createView(), 'user' => $user]);
    }
}
