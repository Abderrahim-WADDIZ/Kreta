<?php

/**
 * This file belongs to Kreta.
 * The source code of application includes a LICENSE file
 * with all information about license.
 *
 * @author benatespina <benatespina@gmail.com>
 * @author gorkalaucirica <gorka.lauzirika@gmail.com>
 */

namespace Kreta\CoreBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Kreta\CoreBundle\Model\Abstracts\AbstractModel;
use Kreta\CoreBundle\Model\Interfaces\CommentInterface;
use Kreta\CoreBundle\Model\Interfaces\IssueInterface;
use Kreta\CoreBundle\Model\Interfaces\LabelInterface;
use Kreta\CoreBundle\Model\Interfaces\UserInterface;

/**
 * Class Issue.
 *
 * @package Kreta\CoreBundle\Model
 */
class Issue extends AbstractModel implements IssueInterface
{
    /**
     * The assignee.
     *
     * @var \Kreta\CoreBundle\Model\Interfaces\UserInterface
     */
    protected $assignee;

    /**
     * Array that contains comments.
     *
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $comments;

    /**
     * The description.
     *
     * @var string
     */
    protected $description;

    /**
     * Array that contains labels.
     *
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $labels;

    /**
     * The priority that can be "low", "medium", "high" or "blocking".
     *
     * @var int
     */
    protected $priority;

    /**
     * The resolution that can be "fixed", "won't fix", "duplicate", "incomplete" or "cannot reproduce".
     *
     * @var int
     */
    protected $resolution;

    /**
     * The reporter.
     *
     * @var \Kreta\CoreBundle\Model\Interfaces\UserInterface
     */
    protected $reporter;

    /**
     * The status that can be "todo", "doing" or "done".
     *
     * @var int
     */
    protected $status;

    /**
     * The title.
     *
     * @var string
     */
    protected $title;

    /**
     * The type that can be "bug", "new feature", "improvement", "epic" or "story".
     *
     * @var int
     */
    protected $type;

    /**
     * Array that contains the watchers.
     *
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $watchers;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->labels = new ArrayCollection();
        $this->watchers = new ArrayCollection();
    }

    /**
     * {@inheritdoc}
     */
    public function getAssignee()
    {
        return $this->assignee;
    }

    /**
     * {@inheritdoc}
     */
    public function setAssignee(UserInterface $assignee)
    {
        $this->assignee = $assignee;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * {@inheritdoc}
     */
    public function addComment(CommentInterface $comment)
    {
        $this->comments[] = $comment;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeComment(CommentInterface $comment)
    {
        $this->comments->removeElement($comment);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * {@inheritdoc}
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getLabels()
    {
        return $this->labels;
    }

    /**
     * {@inheritdoc}
     */
    public function addLabel(LabelInterface $label)
    {
        $this->labels[] = $label;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeLabel(LabelInterface $label)
    {
        $this->labels->removeElement($label);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * {@inheritdoc}
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getReporter()
    {
        return $this->reporter;
    }

    /**
     * {@inheritdoc}
     */
    public function setReporter(UserInterface $reporter)
    {
        $this->reporter = $reporter;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getResolution()
    {
        return $this->resolution;
    }

    /**
     * {@inheritdoc}
     */
    public function setResolution($resolution)
    {
        $this->resolution = $resolution;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * {@inheritdoc}
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * {@inheritdoc}
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * {@inheritdoc}
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getWatchers()
    {
        return $this->watchers;
    }

    /**
     * {@inheritdoc}
     */
    public function addWatcher(UserInterface $watcher)
    {
        $this->watchers[] = $watcher;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeWatcher(UserInterface $watcher)
    {
        $this->watchers->removeElement($watcher);

        return $this;
    }
}
