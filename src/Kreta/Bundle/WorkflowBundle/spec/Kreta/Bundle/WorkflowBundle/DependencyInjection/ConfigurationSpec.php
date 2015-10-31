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

namespace spec\Kreta\Bundle\WorkflowBundle\DependencyInjection;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class ConfigurationSpec.
 *
 * @package spec\Kreta\Bundle\WorkflowBundle\DependencyInjection
 */
class ConfigurationSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Kreta\Bundle\WorkflowBundle\DependencyInjection\Configuration');
    }

    function it_implements_configuration_interface()
    {
        $this->shouldImplement('Symfony\Component\Config\Definition\ConfigurationInterface');
    }

    function it_gets_config_tree_builder()
    {
        $this->getConfigTreeBuilder()
            ->shouldReturnAnInstanceOf('Symfony\Component\Config\Definition\Builder\TreeBuilder');
    }
}
