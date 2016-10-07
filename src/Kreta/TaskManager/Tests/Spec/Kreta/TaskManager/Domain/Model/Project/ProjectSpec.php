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

namespace Spec\Kreta\TaskManager\Domain\Model\Project;

use Kreta\TaskManager\Domain\Model\Project\Project;
use Kreta\TaskManager\Domain\Model\Project\ProjectId;
use PhpSpec\ObjectBehavior;

class ProjectSpec extends ObjectBehavior
{
    function let(ProjectId $projectId)
    {
        $projectId->id()->willReturn('project-id');
        $this->beConstructedWith($projectId);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Project::class);
    }

    function it_gets_id()
    {
        $this->id()->shouldReturnAnInstanceOf(ProjectId::class);
        $this->__toString()->shouldReturn('project-id');
    }
}
