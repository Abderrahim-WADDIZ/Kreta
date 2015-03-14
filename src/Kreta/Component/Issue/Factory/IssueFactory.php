<?php

/**
 * This file belongs to Kreta.
 * The source code of application includes a LICENSE file
 * with all information about license.
 *
 * @author benatespina <benatespina@gmail.com>
 * @author gorkalaucirica <gorka.lauzirika@gmail.com>
 */

namespace Kreta\Component\Issue\Factory;

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
     * @param \Kreta\Component\Project\Model\Interfaces\ProjectInterface|null $project  The project
     * @param \Kreta\Component\User\Model\Interfaces\UserInterface            $reporter User that is creating the issue
     *
     * @return \Kreta\Component\Issue\Model\Interfaces\IssueInterface
     */
    public function create(ProjectInterface $project = null, UserInterface $reporter)
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

        return $issue
            ->setReporter($reporter)
            ->setAssignee($reporter);
    }
}
