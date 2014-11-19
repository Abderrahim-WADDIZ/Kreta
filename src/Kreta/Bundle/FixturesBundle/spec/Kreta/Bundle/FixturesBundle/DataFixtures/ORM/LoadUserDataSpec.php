<?php

/**
 * This file belongs to Kreta.
 * The source code of application includes a LICENSE file
 * with all information about license.
 *
 * @author benatespina <benatespina@gmail.com>
 * @author gorkalaucirica <gorka.lauzirika@gmail.com>
 */

namespace spec\Kreta\Bundle\FixturesBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Kreta\Component\Core\Factory\BaseFactory;
use Kreta\Component\Core\Model\Interfaces\UserInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class LoadUserDataSpec.
 *
 * @package spec\Kreta\Bundle\FixturesBundle
 */
class LoadUserDataSpec extends ObjectBehavior
{
    function let(ContainerInterface $container)
    {
        $this->setContainer($container);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Kreta\Bundle\FixturesBundle\DataFixtures\ORM\LoadUserData');
    }

    function it_extends_data_fixtures()
    {
        $this->shouldHaveType('Kreta\Bundle\FixturesBundle\DataFixtures\DataFixtures');
    }

    function it_loads(
        ContainerInterface $container,
        BaseFactory $factory,
        UserInterface $user,
        ObjectManager $manager
    )
    {
        $this->createUser($container, $factory, $user, 'ROLE_ADMIN');
        $manager->persist($user)->shouldBeCalled();

        $this->createUser($container, $factory, $user);
        $manager->persist($user)->shouldBeCalled();

        $manager->flush()->shouldBeCalled();

        $this->load($manager);
    }

    function it_gets_0_order()
    {
        $this->getOrder()->shouldReturn(0);
    }


    private function createUser(
        ContainerInterface $container,
        BaseFactory $factory,
        UserInterface $user,
        $role = 'ROLE_USER'
    )
    {
        $container->get('kreta_core.factory_user')->shouldBeCalled()->willReturn($factory);
        $factory->create()->shouldBeCalled()->willReturn($user);

        $user->setFirstName(Argument::type('string'))->shouldBeCalled()->willReturn($user);
        $user->setLastName(Argument::type('string'))->shouldBeCalled()->willReturn($user);
        $user->setEmail(Argument::type('string'))->shouldBeCalled()->willReturn($user);
        $user->setPlainPassword(123456)->shouldBeCalled()->willReturn($user);
        $user->setRoles(array($role))->shouldBeCalled()->willReturn($user);
        $user->setEnabled(true)->shouldBeCalled()->willReturn($user);
    }
}
