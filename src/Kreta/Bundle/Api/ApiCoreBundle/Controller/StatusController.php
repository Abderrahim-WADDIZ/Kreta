<?php

/**
 * This file belongs to Kreta.
 * The source code of application includes a LICENSE file
 * with all information about license.
 *
 * @author benatespina <benatespina@gmail.com>
 * @author gorkalaucirica <gorka.lauzirika@gmail.com>
 */

namespace Kreta\Bundle\Api\ApiCoreBundle\Controller;

use FOS\RestBundle\Util\Codes;
use Kreta\Bundle\Api\ApiCoreBundle\Controller\Abstracts\AbstractRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class StatusController.
 *
 * @package Kreta\Bundle\Api\ApiCoreBundle\Controller
 */
class StatusController extends AbstractRestController
{
    /**
     * Returns all the statuses of project id given.
     *
     * @param string $id The project id
     *
     * @ApiDoc(
     *  description = "Returns all the statuses of project id given",
     *  requirements = {
     *    {
     *      "name"="_format",
     *      "requirement"="json|jsonp",
     *      "description"="Supported formats, by default json"
     *    }
     *  },
     *  statusCodes = {
     *    403 = "Not allowed to access this resource",
     *    404 = "Does not exist any project with <$id> id"
     *  }
     * )
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getStatusesAction($id)
    {
        return $this->createResponse($this->getProjectIfAllowed($id)->getStatuses(), ['statusList']);
    }

    /**
     * Returns the status of id and project id given.
     *
     * @param string $projectId The project id
     * @param string $id        The status id
     *
     * @ApiDoc(
     *  description = "Returns the status of id and project id given",
     *  requirements = {
     *    {
     *      "name"="_format",
     *      "requirement"="json|jsonp",
     *      "description"="Supported formats, by default json"
     *    }
     *  },
     *  statusCodes = {
     *    403 = "Not allowed to access this resource",
     *    404 = "Does not exist any project with <$id> id",
     *    404 = "Does not exist any status with <$id> id"
     *  }
     * )
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getStatusAction($projectId, $id)
    {
        return $this->createResponse($this->getStatusIfAllowed($projectId, $id), ['status']);
    }

    /**
     * Creates new status for name, color and type given.
     * Type is a choice option that its values can be 'initial', 'normal' or 'final'.
     *
     * @param string $id The id
     *
     * @ApiDoc(
     *  description = "Creates new status for name, color and type given",
     *  input = "Kreta\Bundle\Api\ApiCoreBundle\Form\Type\StatusType",
     *  output = "Kreta\Component\Core\Model\Interfaces\StatusInterface",
     *  requirements = {
     *    {
     *      "name"="_format",
     *      "requirement"="json|jsonp",
     *      "description"="Supported formats, by default json"
     *    }
     *  },
     *  statusCodes = {
     *      201 = "Successfully created",
     *      400 = {
     *          "Name should not be blank",
     *          "Color should not be blank",
     *          "Type should not be blank",
     *          "This status is already exist in this project",
     *          "The type is not valid"
     *      },
     *      403 = "Not allowed to access this resource",
     *      404 = "Does not exist any project with <$id> id"
     *  }
     * )
     *
     * @throws \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function postStatusesAction($id)
    {
        $name = $this->get('request')->get('name');
        if (!$name) {
            throw new BadRequestHttpException('Name should not be blank');
        }
        $status = $this->get('kreta_core.factory.status')->create($name);
        $status->setProject($this->getProjectIfAllowed($id, 'manage_status'));

        return $this->post(
            $this->get('kreta_api_core.form_handler.status'),
            $status,
            ['status']
        );
    }

    /**
     * Updates the status for name, color and type given.
     * Type is a choice option that its values can be 'initial', 'normal' or 'final'.
     *
     * @ApiDoc(
     *  description = "Updates the status for name, color and type given",
     *  input = "Kreta\Bundle\Api\ApiCoreBundle\Form\Type\StatusType",
     *  output = "Kreta\Component\Core\Model\Interfaces\StatusInterface",
     *  requirements = {
     *    {
     *      "name"="_format",
     *      "requirement"="json|jsonp",
     *      "description"="Supported formats, by default json"
     *    }
     *  },
     *  statusCodes = {
     *      200 = "Successfully created",
     *      400 = {
     *          "Name should not be blank",
     *          "Color should not be blank",
     *          "Type should not be blank",
     *          "This status is already exist in this project",
     *          "The type is not valid"
     *      },
     *      403 = "Not allowed to access this resource",
     *      404 = {
     *          "Does not exist any project with <$id> id",
     *          "Does not exist any status with <$id> id"
     *      }
     *  }
     * )
     *
     * @param string $projectId The project id
     * @param string $id        The id
     *
     * @throws \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function putStatusesAction($projectId, $id)
    {
        return $this->put(
            $this->get('kreta_api_core.form_handler.status'),
            $this->getStatusIfAllowed($projectId, $id, 'manage_status'),
            ['status']
        );
    }

    /**
     * Deletes the status and its associate transitions of project id and id given.
     *
     * @param string $projectId The project id
     * @param string $id        The id
     *
     * @ApiDoc(
     *  description = "Deletes the status and its associate transitions of project id and id given",
     *  requirements = {
     *    {
     *      "name"="_format",
     *      "requirement"="json|jsonp",
     *      "description"="Supported formats, by default json"
     *    }
     *  },
     *  statusCodes = {
     *      204 = "",
     *      403 = {
     *          "Not allowed to access this resource",
     *          "Remove operation has been cancelled, the status is currently in use"
     *      },
     *      404 = {
     *          "Does not exist any project with <$id> id",
     *          "Does not exist any status with <$id> id"
     *      }
     *  }
     * )
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteStatusesAction($projectId, $id)
    {
        $status = $this->getStatusIfAllowed($projectId, $id, 'manage_status');

        $issues = $this->get('kreta_core.repository.issue')->findByProject($status->getProject());
        foreach ($issues as $issue) {
            if ($issue->getStatus()->getId() === $status->getId()) {
                throw new HttpException(
                    Codes::HTTP_FORBIDDEN,
                    'Remove operation has been cancelled, the status is currently in use'
                );
            }
        }

        $this->getRepository()->delete($status);

        return $this->createResponse('', null, Codes::HTTP_NO_CONTENT);
    }

    /**
     * Gets the status if the current user is granted and if the project exists.
     *
     * @param string $projectId The project id
     * @param string $id        The id
     * @param string $grant     The grant, by default 'view'
     *
     * @return \Kreta\Component\Core\Model\Interfaces\StatusInterface
     */
    protected function getStatusIfAllowed($projectId, $id, $grant = 'view')
    {
        $this->getProjectIfAllowed($projectId, $grant);

        return $this->getResourceIfExists($id);
    }

    /**
     * {@inheritdoc}
     */
    protected function getRepository()
    {
        return $this->get('kreta_core.repository.status');
    }
}
