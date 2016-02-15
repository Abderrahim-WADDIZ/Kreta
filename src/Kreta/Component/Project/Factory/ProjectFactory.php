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

namespace Kreta\Component\Project\Factory;

use Kreta\Component\Media\Factory\MediaFactory;
use Kreta\Component\Media\Uploader\Interfaces\MediaUploaderInterface;
use Kreta\Component\Organization\Model\Interfaces\OrganizationInterface;
use Kreta\Component\Project\Model\Interfaces\ProjectInterface;
use Kreta\Component\Project\Model\IssuePriority;
use Kreta\Component\User\Model\Interfaces\UserInterface;
use Kreta\Component\Workflow\Factory\WorkflowFactory;
use Kreta\Component\Workflow\Model\Interfaces\WorkflowInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class ProjectFactory.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class ProjectFactory
{
    const DEFAULT_WORKFLOW_NAME = 'Default KRETA workflow';

    /**
     * The class name.
     *
     * @var string
     */
    protected $className;

    /**
     * The participant factory.
     *
     * @var ParticipantFactory
     */
    protected $participantFactory;

    /**
     * The workflow factory.
     *
     * @var WorkflowFactory
     */
    protected $workflowFactory;

    /**
     * The media factory.
     *
     * @var MediaFactory
     */
    protected $mediaFactory;

    /**
     * The uploader.
     *
     * @var MediaUploaderInterface
     */
    protected $uploader;

    /**
     * Constructor.
     *
     * @param string                 $className          The class name
     * @param ParticipantFactory     $participantFactory Factory needed to add creator as participant
     * @param WorkflowFactory        $workflowFactory    Factory needed to add workflow by default
     * @param MediaFactory           $mediaFactory       The media factory
     * @param MediaUploaderInterface $uploader           The uploader
     */
    public function __construct(
        $className,
        ParticipantFactory $participantFactory,
        WorkflowFactory $workflowFactory,
        MediaFactory $mediaFactory,
        MediaUploaderInterface $uploader
    ) {
        $this->className = $className;
        $this->participantFactory = $participantFactory;
        $this->workflowFactory = $workflowFactory;
        $this->mediaFactory = $mediaFactory;
        $this->uploader = $uploader;
    }

    /**
     * Creates an instance of a project.
     *
     * @param UserInterface              $user         The project creator
     * @param OrganizationInterface|null $organization The organization
     * @param WorkflowInterface|null     $workflow     The workflow
     * @param boolean                    $load         Load boolean, by default true
     * @param UploadedFile               $image        The image, can be null
     *
     * @return ProjectInterface
     */
    public function create(
        UserInterface $user,
        OrganizationInterface $organization = null,
        WorkflowInterface $workflow = null,
        $load = true,
        UploadedFile $image = null
    ) {
        $project = new $this->className();

        $participant = $this->participantFactory->create($project, $user, 'ROLE_ADMIN');
        if (!($workflow instanceof WorkflowInterface)) {
            $workflow = $this->workflowFactory->create(self::DEFAULT_WORKFLOW_NAME, $user, true);
        }

        if ($load) {
            $project = $this->loadPrioritiesAndTypes($project);
        }

        if ($image instanceof UploadedFile) {
            $media = $this->mediaFactory->create($image);
            $this->uploader->upload($media);
            $project->setImage($media);
        }

        return $project
            ->setCreator($user)
            ->setOrganization($organization)
            ->addParticipant($participant)
            ->setWorkflow($workflow);
    }

    /**
     * Loads the default issue priorities and types.
     *
     * @param ProjectInterface $project The project
     *
     * @return ProjectInterface
     */
    protected function loadPrioritiesAndTypes(
        ProjectInterface $project
    ) {
        $priorities = $this->createDefaultPriorities();
        foreach ($priorities as $priority) {
            $priority->setProject($project);
            $project->addIssuePriority($priority);
        }

        return $project;
    }

    /**
     * Creates some default priorities to add into project when this is created.
     *
     * @return \Kreta\Component\Project\Model\Interfaces\IssuePriorityInterface[]
     */
    protected function createDefaultPriorities()
    {
        $defaultPriorities = [
            'Low' => '#969696', 'Medium' => '#67b86a', 'High' => '#f07f2c', 'Blocker' => '#f02c4c',
        ];
        $priorities = [];
        foreach ($defaultPriorities as $name => $color) {
            $priority = new IssuePriority();
            $priority->setName($name);
            $priority->setColor($color);
            $priorities[$name] = $priority;
        }

        return $priorities;
    }
}
