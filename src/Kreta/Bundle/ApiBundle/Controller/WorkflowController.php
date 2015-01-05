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

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Request\ParamFetcher;
use Kreta\Bundle\ApiBundle\Controller\Abstracts\AbstractRestController;

/**
 * Class WorkflowController.
 *
 * @package Kreta\Bundle\ApiBundle\Controller
 */
class WorkflowController extends AbstractRestController
{
    /**
     * Returns all the workflows of current user, it admits sort, limit and offset.
     *
     * @param \FOS\RestBundle\Request\ParamFetcher $paramFetcher The param fetcher
     *
     * @QueryParam(name="sort", requirements="(name|createdAt)", default="name", description="Sort")
     * @QueryParam(name="limit", requirements="\d+", default="9999", description="Amount of workflows to be returned")
     * @QueryParam(name="offset", requirements="\d+", default="0", description="Offset in pages")
     *
     * @ApiDoc(
     *  description = "Returns all the workflows of current user, it admits sort, limit and offset",
     *  requirements = {
     *    {
     *      "name"="_format",
     *      "requirement"="json|jsonp",
     *      "description"="Supported formats, by default json"
     *    }
     *  },
     *  resource = true,
     *  statusCodes = {
     *    200 = "<data>"
     *  }
     * )
     *
     * @View(
     *  statusCode=200,
     *  serializerGroups={"workflowList"}
     * )
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getWorkflowsAction(ParamFetcher $paramFetcher)
    {
        return $this->getRepository()->findBy(
            ['creator' => $this->getCurrentUser()],
            [$paramFetcher->get('sort') => 'ASC'],
            $paramFetcher->get('limit'),
            $paramFetcher->get('offset')
        );
    }

    /**
     * Returns the workflow of id given.
     *
     * @param string $workflowId The workflow id
     *
     * @ApiDoc(
     *  description = "Returns the workflow of id given",
     *  requirements = {
     *    {
     *      "name"="_format",
     *      "requirement"="json|jsonp",
     *      "description"="Supported formats, by default json"
     *    }
     *  },
     *  statusCodes = {
     *    200 = "<data>",
     *    403 = "Not allowed to access this resource",
     *    404 = "Does not exist any object with id passed"
     *  }
     * )
     *
     * @View(
     *  statusCode=200,
     *  serializerGroups={"workflow"}
     * )
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getWorkflowAction($workflowId)
    {
        return $this->getWorkflowIfAllowed($workflowId);
    }

    /**
     * Creates new workflow for name given.
     *
     * @ApiDoc(
     *  description = "Creates new workflow for name given",
     *  input = "Kreta\Bundle\ApiBundle\Form\Type\WorkflowType",
     *  output = "Kreta\Component\Workflow\Model\Interfaces\WorkflowInterface",
     *  requirements = {
     *    {
     *      "name"="_format",
     *      "requirement"="json|jsonp",
     *      "description"="Supported formats, by default json"
     *    }
     *  },
     *  statusCodes = {
     *      201 = "<data>",
     *      400 = {
     *          "Name should not be blank",
     *          "A workflow with identical name is already exists",
     *      }
     *  }
     * )
     *
     * @View(
     *  statusCode=201,
     *  serializerGroups={"workflow"}
     * )
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function postWorkflowAction()
    {
        return $this->get('kreta_api.form_handler.workflow')->processForm($this->get('request'));
    }

    /**
     * Updates the workflow of id given.
     *
     * @param string $workflowId The workflow id
     *
     * @ApiDoc(
     *  description = "Updates the workflow of id given",
     *  input = "Kreta\Bundle\ApiBundle\Form\Type\WorkflowType",
     *  output = "Kreta\Component\Workflow\Model\Interfaces\WorkflowInterface",
     *  requirements = {
     *    {
     *      "name"="_format",
     *      "requirement"="json|jsonp",
     *      "description"="Supported formats, by default json"
     *    }
     *  },
     *  statusCodes = {
     *      200 = "<data>",
     *      400 = {
     *          "Name should not be blank",
     *          "A workflow with identical name is already exists",
     *      },
     *      403 = "Not allowed to access this resource",
     *      404 = "Does not exist any object with id passed"
     *  }
     * )
     *
     * @View(
     *  statusCode=200,
     *  serializerGroups={"workflow"}
     * )
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function putWorkflowAction($workflowId)
    {
        return $this->get('kreta_api.form_handler.workflow')->processForm(
            $this->get('request'), $this->getWorkflowIfAllowed($workflowId, 'edit'), ['method' => 'PUT']
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function getRepository()
    {
        return $this->get('kreta_workflow.repository.workflow');
    }
}
