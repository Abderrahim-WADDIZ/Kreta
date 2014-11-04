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

use Kreta\Bundle\WebBundle\Form\Type\CommentType;
use Kreta\Bundle\WebBundle\Form\Type\IssueType;
use Kreta\Component\Core\Model\Interfaces\IssueInterface;
use Kreta\Component\Core\Model\Interfaces\StatusInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Class IssueController.
 *
 * @package Kreta\Bundle\WebBundle\Controller
 */
class IssueController extends Controller
{
    /**
     * View action.
     *
     * @param string $id The id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewAction($id)
    {
        $issue = $this->get('kreta_core.repository_issue')->find($id);

        if (($issue instanceof IssueInterface) === false) {
            $this->createNotFoundException();
        }

        if ($this->get('security.context')->isGranted('edit', $issue) === false) {
            throw new AccessDeniedException();
        };

        return $this->render('KretaWebBundle:Issue:view.html.twig', array('issue' => $issue));
    }

    /**
     * New action.
     *
     * @param integer                                   $projectId Id of the project
     * @param \Symfony\Component\HttpFoundation\Request $request   The request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction($projectId, Request $request)
    {
        $issue = $this->get('kreta_core.factory_issue')->create();

        /** @var \Kreta\Component\Core\Model\Interfaces\ProjectInterface $project */
        $project = $this->get('kreta_core.repository_project')->find($projectId);

        if (!$project || $this->get('security.context')->isGranted('create_issue', $project)) {
            throw new AccessDeniedException();
        }

        $issue->setProject($project);
        $user = $this->getUser();
        $issue->setReporter($user);
        $issue->setAssignee($user);

        $form = $this->createForm(new IssueType($project->getParticipants()), $issue);

        if ($request->isMethod('POST') === true) {
            $form->handleRequest($request);
            if ($form->isSubmitted() === true && $form->isValid() === true) {
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($issue);
                $manager->flush();
                $this->get('session')->getFlashBag()->add('success', 'Issue created successfully');

                return $this->redirect($this->generateUrl('kreta_web_issue_view', array('id' => $issue->getId())));
            }
            $this->get('session')->getFlashBag()->add('error', 'Some errors found in your issue');
        }

        return $this->render('KretaWebBundle:Issue:new.html.twig',
            array('form' => $form->createView(), 'project' => $project));
    }

    /**
     * Edit action.
     *
     * @param string                                    $id      The id
     * @param \Symfony\Component\HttpFoundation\Request $request The request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction($id, Request $request)
    {
        /** @var IssueInterface $issue */
        $issue = $this->get('kreta_core.repository_issue')->find($id);

        if (($issue instanceof IssueInterface) === false) {
            $this->createNotFoundException();
        }

        if ($this->get('security.context')->isGranted('edit', $issue) === false) {
            throw new AccessDeniedException();
        };

        $form = $this->createForm(new IssueType($issue->getProject()->getParticipants()), $issue);

        if ($request->isMethod('POST') === true) {
            $form->handleRequest($request);
            if ($form->isSubmitted() === true && $form->isValid() === true) {
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($issue);
                $manager->flush();
                $this->get('session')->getFlashBag()->add('success', 'Issue edited successfully');

                return $this->redirect($this->generateUrl('kreta_web_issue_view', array('id' => $issue->getId())));
            }
            $this->get('session')->getFlashBag()->add('error', 'Some errors found in your issue');
        }

        return $this->render('KretaWebBundle:Issue:edit.html.twig', array(
            'form' => $form->createView(),
            'issue' => $issue
        ));
    }

    /**
     * New comment action.
     *
     * @param string                                    $issueId The issue id
     * @param \Symfony\Component\HttpFoundation\Request $request The request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newCommentAction($issueId, Request $request)
    {
        $issue = $this->get('kreta_core.repository_issue')->find($issueId);

        if (($issue instanceof IssueInterface) === false) {
            $this->createNotFoundException('Issue not found');
        }

        $comment = $this->get('kreta_core.factory_comment')->create();

        $form = $this->createForm(new CommentType(), $comment);

        if ($request->isMethod('POST') === true) {
            $form->handleRequest($request);
            if ($form->isSubmitted() === true && $form->isValid() === true) {
                $comment->setWrittenBy($this->getUser());
                $comment->setIssue($issue);
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($comment);
                $manager->flush();
                $this->get('session')->getFlashBag()->add('success', 'Comment added successfully');

                return $this->redirect($this->generateUrl('kreta_web_issue_view', array('id' => $issue->getId())));
            }

            return $this->redirect($this->generateUrl('kreta_web_issue_view', array('id' => $issue->getId())));
        }

        return $this->render('KretaWebBundle:Issue/blocks:commentForm.html.twig', array(
            'form' => $form->createView(),
            'issue' => $issue
        ));
    }

    /**
     * New comment action.
     *
     * @param string $issueId  The issue id
     * @param string $statusId The issue id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function editStatusAction($issueId, $statusId)
    {
        /** @var IssueInterface $issue */
        $issue = $this->get('kreta_core.repository_issue')->find($issueId);

        if (($issue instanceof IssueInterface) === false) {
            $this->createNotFoundException();
        }

        if ($this->get('security.context')->isGranted('edit', $issue) === false) {
            throw new AccessDeniedException();
        };

        /** @var StatusInterface $status */
        $status = $this->get('kreta_core.repository_status')->find($statusId);

        if (($issue instanceof StatusInterface) === false) {
            $this->createNotFoundException();
        }

        $statuses = $this->get('kreta_core.repository_status')->findByProject($issue->getProject());

        $stateMachine = $this->get('kreta_issue_state_machine')->load($issue, $statuses);

        if($stateMachine->can($issue->getStatus()->getName() . '-' . $status->getName())) {
            $manager = $this->getDoctrine()->getManager();
            $issue->setStatus($status);
            $manager->persist($issue);
            $manager->flush();
            $this->get('session')->getFlashBag()->add('success', 'Status changed successfully');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'Transition not allowed');
        }

        return $this->redirect($this->generateUrl('kreta_web_issue_view', array('id' => $issue->getId())));
    }
}
