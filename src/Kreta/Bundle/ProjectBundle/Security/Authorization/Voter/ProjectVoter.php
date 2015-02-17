<?php

/**
 * This file belongs to Kreta.
 * The source code of application includes a LICENSE file
 * with all information about license.
 *
 * @author benatespina <benatespina@gmail.com>
 * @author gorkalaucirica <gorka.lauzirika@gmail.com>
 */

namespace Kreta\Bundle\ProjectBundle\Security\Authorization\Voter;

use Kreta\Bundle\CoreBundle\Security\Authorization\Voter\Abstracts\AbstractVoter;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class ProjectVoter.
 *
 * @package Kreta\Bundle\ProjectBundle\Security\Authorization\Voter
 */
class ProjectVoter extends AbstractVoter
{
    const ADD_PARTICIPANT = 'add_participant';
    const DELETE = 'delete';
    const DELETE_PARTICIPANT = 'delete_participant';
    const EDIT = 'edit';
    const VIEW = 'view';
    const CREATE_ISSUE = 'create_issue';
    const CREATE_LABEL = 'create_label';
    const DELETE_LABEL = 'delete_label';

    /**
     * {@inheritdoc}
     */
    protected $attributes = [
        self::ADD_PARTICIPANT,
        self::DELETE,
        self::DELETE_PARTICIPANT,
        self::EDIT,
        self::VIEW,
        self::CREATE_ISSUE,
        self::CREATE_LABEL,
        self::DELETE_LABEL
    ];

    /**
     * {@inheritdoc}
     */
    protected $supportedClass = 'Kreta\Component\Project\Model\Interfaces\ProjectInterface';

    /**
     * {@inheritdoc}
     */
    public function checkAttribute(UserInterface $user, $project, $attribute)
    {
        switch ($attribute) {
            case self::ADD_PARTICIPANT:
            case self::DELETE:
            case self::DELETE_PARTICIPANT:
            case self::EDIT:
            case self::DELETE_LABEL:
                if ($project->getUserRole($user) === 'ROLE_ADMIN') {
                    return VoterInterface::ACCESS_GRANTED;
                }
                break;
            case self::VIEW:
            case self::CREATE_ISSUE:
            case self::CREATE_LABEL:
                if ($project->getUserRole($user) !== null) {
                    return VoterInterface::ACCESS_GRANTED;
                }
                break;
        }

        return VoterInterface::ACCESS_DENIED;
    }
}
