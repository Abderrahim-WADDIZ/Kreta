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

namespace Kreta\Component\Issue\Factory;

use Kreta\Component\Issue\Model\Interfaces\IssueInterface;
use Kreta\Component\Project\Model\Interfaces\IssuePriorityInterface;
use Kreta\Component\Project\Model\Interfaces\ProjectInterface;
use Kreta\Component\User\Model\Interfaces\UserInterface;

/**
 * Class IssueFactory.
 *
 * @package Kreta\Component\Issue\Factory
 */
class IssueFactory
{
    /**
     * The class name.
     *
     * @var string
     */
    protected $className;

    /**
     * Constructor.
     *
     * @param string $className The class name
     */
    public function __construct($className)
    {
        $this->className = $className;
    }

    /**
     * Creates an instance of issue.
     *
     * @param \Kreta\Component\User\Model\Interfaces\UserInterface             $reporter      User that is the reporter
     * @param \Kreta\Component\Project\Model\Interfaces\IssuePriorityInterface $issuePriority The priority
     * @param \Kreta\Component\Project\Model\Interfaces\ProjectInterface|null  $project       The project
     * @param \Kreta\Component\Issue\Model\Interfaces\IssueInterface           $parent        The parent issue
     *
     * @return \Kreta\Component\Issue\Model\Interfaces\IssueInterface
     */
    public function create(
        UserInterface $reporter,
        IssuePriorityInterface $issuePriority,
        ProjectInterface $project = null,
        IssueInterface $parent = null
    )
    {
        $issue = new $this->className();

        if ($project instanceof ProjectInterface) {
            $issue->setProject($project);
            $statuses = $project->getWorkflow()->getStatuses();
            foreach ($statuses as $status) {
                if ($status->getType() === 'initial') {
                    $issue->setStatus($status);
                    break;
                }
            }
        }
        if ($parent instanceof IssueInterface) {
            $issue->setParent($parent);
        }

        return $issue
            ->setPriority($issuePriority)
            ->setReporter($reporter)
            ->setAssignee($reporter);
    }
}
