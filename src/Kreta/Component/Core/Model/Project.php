<?php

/**
 * This file belongs to Kreta.
 * The source code of application includes a LICENSE file
 * with all information about license.
 *
 * @author benatespina <benatespina@gmail.com>
 * @author gorkalaucirica <gorka.lauzirika@gmail.com>
 */

namespace Kreta\Component\Core\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Kreta\Component\Core\Model\Abstracts\AbstractModel;
use Kreta\Component\Core\Model\Interfaces\IssueInterface;
use Kreta\Component\Core\Model\Interfaces\ParticipantInterface;
use Kreta\Component\Core\Model\Interfaces\ProjectInterface;
use Kreta\Component\Core\Model\Interfaces\StatusInterface;
use Kreta\Component\Core\Model\Interfaces\StatusTransitionInterface;
use Kreta\Component\Core\Model\Interfaces\UserInterface;

/**
 * Class Project.
 *
 * @package Kreta\Component\Core\Model
 */
class Project extends AbstractModel implements ProjectInterface
{
    /**
     * Array that contains all the issues of the project.
     *
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $issues;

    /**
     * The name.
     *
     * @var string
     */
    protected $name;

    /**
     * Array that contains all the roles of the project.
     *
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $participants;

    /**
     * The short name.
     *
     * @var string
     */
    protected $shortName;

    /**
     * Array that contains all the status of the project.
     *
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $statuses;

    /**
     * Array that contains all the status transitions of the project.
     *
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $statusTransitions;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->issues = new ArrayCollection();
        $this->participants = new ArrayCollection();
        $this->statuses = new ArrayCollection();
        $this->statusTransitions = new ArrayCollection();
    }

    /**
     * {@inheritdoc}
     */
    public function getIssues()
    {
        return $this->issues;
    }

    /**
     * {@inheritdoc}
     */
    public function addIssue(IssueInterface $issue)
    {
        $this->issues[] = $issue;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeIssue(IssueInterface $issue)
    {
        $this->issues->removeElement($issue);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function setName($name)
    {
        $this->name = $name;

        if ($this->shortName === null) {
            $this->shortName = substr($this->name, 0, 26) . '...';
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getParticipants()
    {
        return $this->participants;
    }

    /**
     * {@inheritdoc}
     */
    public function addParticipant(ParticipantInterface $participant)
    {
        $this->participants[] = $participant;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeParticipant(ParticipantInterface $participant)
    {
        $this->participants->removeElement($participant);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getShortName()
    {
        return $this->shortName;
    }

    /**
     * {@inheritdoc}
     */
    public function setShortName($shortName)
    {
        if (strlen($shortName) > 26) {
            $this->shortName = substr($shortName, 0, 26) . '...';

            return $this;
        }

        $this->shortName = $shortName;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getStatuses()
    {
        return $this->statuses;
    }

    /**
     * {@inheritdoc}
     */
    public function addStatus(StatusInterface $status)
    {
        $this->statuses[] = $status;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeStatus(StatusInterface $status)
    {
        $this->statuses->removeElement($status);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getStatusTransitions()
    {
        return $this->statusTransitions;
    }

    /**
     * {@inheritdoc}
     */
    public function addStatusTransition(StatusTransitionInterface $statusTransition)
    {
        $this->statusTransitions[] = $statusTransition;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeStatusTransition(StatusTransitionInterface $statusTransition)
    {
        $this->statusTransitions->removeElement($statusTransition);

        return $this;
    }


    /**
     * {@inheritdoc}
     */
    public function getUserRole(UserInterface $user)
    {
        foreach ($this->participants as $participant) {
            if ($participant->getUser()->getId() === $user->getId()) {
                return $participant->getRole();
            }
        }

        return null;
    }
}
