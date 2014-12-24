<?php

/**
 * This file belongs to Kreta.
 * The source code of application includes a LICENSE file
 * with all information about license.
 *
 * @author benatespina <benatespina@gmail.com>
 * @author gorkalaucirica <gorka.lauzirika@gmail.com>
 */

namespace Kreta\Bundle\ApiBundle\Controller;

use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\Controller\Annotations\Get;
use Kreta\Bundle\ApiBundle\Controller\Abstracts\AbstractRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class StatusTransitionController.
 *
 * @package Kreta\Bundle\ApiBundle\Controller
 */
class StatusTransitionController extends AbstractRestController
{
    /**
     * Returns transitions of workflow id given.
     *
     * @param string $workflowId The workflow id
     *
     * @ApiDoc(
     *  description = "Returns transitions of workflow id given",
     *  requirements = {
     *    {
     *      "name"="_format",
     *      "requirement"="json|jsonp",
     *      "description"="Supported formats, by default json"
     *    }
     *  },
     *  statusCodes = {
     *    403 = "Not allowed to access this resource",
     *    404 = "Does not exist any workflow with <$id> id"
     *  }
     * )
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getTransitionsAction($workflowId)
    {
        return $this->createResponse(
            $this->getWorkflowIfAllowed($workflowId)->getStatusTransitions(), ['transitionList']
        );
    }

    /**
     * Returns the transition of workflow id and status transition id given.
     *
     * @param string $workflowId   The workflow id
     * @param string $transitionId The status transition id
     *
     * @ApiDoc(
     *  description = "Returns the transition of workflow id and status transition id given",
     *  requirements = {
     *    {
     *      "name"="_format",
     *      "requirement"="json|jsonp",
     *      "description"="Supported formats, by default json"
     *    }
     *  },
     *  statusCodes = {
     *    403 = "Not allowed to access this resource",
     *    404 = "Does not exist any workflow with <$id> id",
     *    404 = "Does not exist any transition with <$id> id"
     *  }
     * )
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getTransitionAction($workflowId, $transitionId)
    {
        return $this->createResponse($this->getTransitionIfAllowed($workflowId, $transitionId), ['transition']);
    }

    /**
     * Deletes the transition of workflow id and transition id given.
     *
     * @param string $workflowId   The workflow id
     * @param string $transitionId The transition id
     *
     * @ApiDoc(
     *  description = "Deletes the transition of workflow id and transition id given",
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
     *          "Remove operation has been cancelled, the transition is currently in use"
     *      },
     *      404 = {
     *          "Does not exist any project with <$id> id",
     *          "Does not exist any status with <$id> id",
     *          "Does not exist any transition with <$id> id"
     *      }
     *  }
     * )
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteTransitionAction($workflowId, $transitionId)
    {
        $transition = $this->getTransitionIfAllowed($workflowId, $transitionId, 'manage_status');

        $issues = $this->get('kreta_issue.repository.issue')->findByWorkflow($transition->getWorkflow());
        foreach ($issues as $issue) {
            foreach ($issue->getStatus()->getTransitions() as $retrieveTransition) {
                if ($retrieveTransition->getId() === $transition->getId()) {
                    throw new HttpException(
                        Codes::HTTP_FORBIDDEN,
                        'Remove operation has been cancelled, the transition is currently in use'
                    );
                }
            }
        }

        $this->getRepository()->delete($transition);

        return $this->createResponse('', null, Codes::HTTP_NO_CONTENT);
    }

    /**
     * Gets the status transition if the current user is granted and if the workflow exists.
     *
     * @param string $workflowId   The workflow id
     * @param string $transitionId The transition id
     * @param string $grant        The grant, by default 'view'
     *
     * @return \Kreta\Component\Workflow\Model\Interfaces\StatusTransitionInterface
     */
    protected function getTransitionIfAllowed($workflowId, $transitionId, $grant = 'view')
    {
        $this->getWorkflowIfAllowed($workflowId, $grant);

        return $this->getResourceIfExists($transitionId);
    }

    /**
     * {@inheritdoc}
     */
    protected function getRepository()
    {
        return $this->get('kreta_workflow.repository.status_transition');
    }
}
