<?php

/**
 * This file belongs to Kreta.
 * The source code of application includes a LICENSE file
 * with all information about license.
 *
 * @author benatespina <benatespina@gmail.com>
 * @author gorkalaucirica <gorka.lauzirika@gmail.com>
 */

namespace Kreta\Component\Core\Factory;

use Kreta\Component\Core\Model\Interfaces\ProjectInterface;
use Kreta\Component\Core\Model\Interfaces\UserInterface;

/**
 * Class ParticipantFactory.
 *
 * @package Kreta\Component\Core\Factory
 */
class ParticipantFactory
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
     * Creates an instance of an entity.
     *
     * @param \Kreta\Component\Core\Model\Interfaces\ProjectInterface $project The project
     * @param \Kreta\Component\Core\Model\Interfaces\UserInterface    $user    The user
     *
     * @return \Kreta\Component\Core\Model\Interfaces\ParticipantInterface
     */
    public function create(ProjectInterface $project, UserInterface $user, $role = 'ROLE_PARTICIPANT')
    {
        $participant = new $this->className($project, $user);

        return $participant->setRole($role);
    }
}
