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

namespace Kreta\Bundle\WorkflowBundle\Controller;

use FOS\RestBundle\Controller\Annotations\View;
use Kreta\Component\Core\Annotation\ResourceIfAllowed as Workflow;
use Kreta\SimpleApiDocBundle\Annotation\ApiDoc;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class WorkflowController.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class WorkflowController extends Controller
{
    /**
     * Returns all the workflows.
     *
     * @ApiDoc(resource=true, statusCodes={200})
     * @View(statusCode=200, serializerGroups={"workflowList"})
     *
     * @return \Kreta\Component\Workflow\Model\Interfaces\WorkflowInterface[]
     */
    public function getWorkflowsAction()
    {
        return $this->get('kreta_workflow.repository.workflow')->findAll();
    }

    /**
     * Returns the workflow of id given.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    The request
     * @param string                                    $workflowId The workflow id
     *
     * @ApiDoc(statusCodes={200, 403, 404})
     * @View(statusCode=200, serializerGroups={"workflow"})
     * @Workflow()
     *
     * @return \Kreta\Component\Workflow\Model\Interfaces\WorkflowInterface
     */
    public function getWorkflowAction(Request $request, $workflowId)
    {
        return $request->get('workflow');
    }

    /**
     * Creates new workflow for name given.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request The request
     *
     * @ApiDoc(statusCodes={201, 400})
     * @View(statusCode=201, serializerGroups={"workflow"})
     *
     * @return \Kreta\Component\Workflow\Model\Interfaces\WorkflowInterface
     */
    public function postWorkflowAction(Request $request)
    {
        return $this->get('kreta_workflow.form_handler.workflow')->processForm($request);
    }

    /**
     * Updates the workflow of id given.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    The request
     * @param string                                    $workflowId The workflow id
     *
     * @ApiDoc(statusCodes={200, 400, 403, 404})
     * @View(statusCode=200, serializerGroups={"workflow"})
     * @Workflow("edit")
     *
     * @return \Kreta\Component\Workflow\Model\Interfaces\WorkflowInterface
     */
    public function putWorkflowAction(Request $request, $workflowId)
    {
        return $this->get('kreta_workflow.form_handler.workflow')->processForm(
            $request, $request->get('workflow'), ['method' => 'PUT']
        );
    }
}
