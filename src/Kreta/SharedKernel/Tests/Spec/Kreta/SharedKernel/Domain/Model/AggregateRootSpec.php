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

namespace Spec\Kreta\SharedKernel\Domain\Model;

use Kreta\SharedKernel\Domain\Model\AggregateRoot;
use Kreta\SharedKernel\Tests\Double\Domain\Model\AggregateRootStub;
use PhpSpec\ObjectBehavior;

class AggregateRootSpec extends ObjectBehavior
{
    function let()
    {
        $this->beAnInstanceOf(AggregateRootStub::class);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(AggregateRoot::class);
    }

    function it_gets_recorded_events()
    {
        $this->recordedEvents()->shouldBeArray();
    }

    function it_publishes_an_event_like_event_sourcing()
    {
        $this->recordedEvents()->shouldHaveCount(0);
        $this->foo();
        $this->recordedEvents()->shouldHaveCount(1);
        $this->property()->shouldReturn('foo');
    }

    function it_publishes_an_event_like_cqrs()
    {
        $this->recordedEvents()->shouldHaveCount(0);
        $this->bar();
        $this->recordedEvents()->shouldHaveCount(1);
        $this->property()->shouldReturn('bar');
    }
}
