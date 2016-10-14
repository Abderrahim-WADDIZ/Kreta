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

namespace Spec\Kreta\TaskManager\Domain\Model\Project\Task;

use Kreta\SharedKernel\Domain\Model\Exception;
use Kreta\TaskManager\Domain\Model\Project\Task\TaskPriorityNotAllowedException;
use PhpSpec\ObjectBehavior;

class TaskPriorityNotAllowedExceptionSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('invalid-priority');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(TaskPriorityNotAllowedException::class);
        $this->shouldHaveType(Exception::class);
    }

    function it_returns_a_message()
    {
        $this->getMessage()->shouldReturn('Priority "invalid-priority" not allowed');
    }
}
